<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DbSyncConfig;
use App\Models\DbSyncLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Exception;

class DbSyncController extends Controller
{
    public function index()
    {
        $configs = DbSyncConfig::with(['logs' => function ($q) {
            $q->latest('iniciado_en')->take(5);
        }])->get();

        return view('admin.db-sync.index', compact('configs'));
    }

    public function create()
    {
        return view('admin.db-sync.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:db_sync_configs',
            'host' => 'required|string|max:255',
            'puerto' => 'required|integer|min:1|max:65535',
            'database' => 'required|string|max:255',
            'usuario' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'activo' => 'sometimes|boolean',
            'tablas_excluir' => 'nullable|string',
            'tablas_solo_estructura' => 'nullable|string',
        ]);

        $config = DbSyncConfig::create([
            'nombre' => $validated['nombre'],
            'host' => $validated['host'],
            'puerto' => $validated['puerto'],
            'database' => $validated['database'],
            'usuario' => $validated['usuario'],
            'password' => $validated['password'],
            'activo' => $request->boolean('activo'),
            'tablas_excluir' => $validated['tablas_excluir'] ? array_map('trim', explode(',', $validated['tablas_excluir'])) : [],
            'tablas_solo_estructura' => $validated['tablas_solo_estructura'] ? array_map('trim', explode(',', $validated['tablas_solo_estructura'])) : [],
        ]);

        return redirect()->route('admin.db-sync.index')->with('success', 'Configuración guardada. La contraseña queda encriptada.');
    }

    public function edit(DbSyncConfig $dbSyncConfig)
    {
        return view('admin.db-sync.edit', ['config' => $dbSyncConfig]);
    }

    public function update(Request $request, DbSyncConfig $dbSyncConfig)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:db_sync_configs,nombre,' . $dbSyncConfig->id,
            'host' => 'required|string|max:255',
            'puerto' => 'required|integer|min:1|max:65535',
            'database' => 'required|string|max:255',
            'usuario' => 'required|string|max:255',
            'password' => 'nullable|string|max:255',
            'activo' => 'sometimes|boolean',
            'tablas_excluir' => 'nullable|string',
            'tablas_solo_estructura' => 'nullable|string',
        ]);

        $data = [
            'nombre' => $validated['nombre'],
            'host' => $validated['host'],
            'puerto' => $validated['puerto'],
            'database' => $validated['database'],
            'usuario' => $validated['usuario'],
            'activo' => $request->boolean('activo'),
            'tablas_excluir' => $validated['tablas_excluir'] ? array_map('trim', explode(',', $validated['tablas_excluir'])) : [],
            'tablas_solo_estructura' => $validated['tablas_solo_estructura'] ? array_map('trim', explode(',', $validated['tablas_solo_estructura'])) : [],
        ];

        if ($request->filled('password')) {
            $data['password'] = $validated['password'];
        }

        $dbSyncConfig->update($data);

        return redirect()->route('admin.db-sync.index')->with('success', 'Configuración actualizada.');
    }

    public function destroy(DbSyncConfig $dbSyncConfig)
    {
        $dbSyncConfig->delete();
        return redirect()->route('admin.db-sync.index')->with('success', 'Configuración eliminada.');
    }

    public function testConnection(DbSyncConfig $dbSyncConfig)
    {
        $config = $dbSyncConfig;
        $dbg = [
            'id' => $config->id,
            'host' => $config->host,
            'puerto' => $config->puerto,
            'database' => $config->database,
            'usuario' => $config->usuario,
            'pass_len' => strlen($config->password),
        ];
        try {
            file_put_contents(storage_path('logs/dbsync_debug.txt'), json_encode($dbg) . PHP_EOL, FILE_APPEND);
            $pdo = $this->getRemotePdo($config);
            $pdo->query('SELECT 1');
            return response()->json(['ok' => true, 'msg' => 'Conexión exitosa', 'debug' => $dbg]);
        } catch (Exception $e) {
            file_put_contents(storage_path('logs/dbsync_debug.txt'), 'ERROR: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
            return response()->json(['ok' => false, 'msg' => $e->getMessage(), 'debug' => $dbg], 500);
        }
    }

    public function sync(Request $request, DbSyncConfig $dbSyncConfig)
    {
        $config = $dbSyncConfig;
        $direccion = $request->input('direccion', 'bidireccional'); // remote_to_local | local_to_remote | bidireccional

        $log = DbSyncLog::create([
            'db_sync_config_id' => $config->id,
            'direccion' => $direccion,
            'fase' => 'comparando',
            'estado' => 'ejecutando',
            'ejecutado_por' => auth()->id(),
        ]);

        try {
            $resumen = [
                'tablas_procesadas' => 0,
                'filas_insertadas' => 0,
                'filas_actualizadas' => 0,
                'filas_eliminadas' => 0,
                'errores' => [],
            ];

            $remotePdo = $this->getRemotePdo($config);
            $localPdo = DB::connection()->getPdo();

            $tablas = $this->getTablasComunes($localPdo, $remotePdo, $config);

            foreach ($tablas as $tabla) {
                if (in_array($tabla, $config->tablas_excluir ?? [])) {
                    continue;
                }

                $soloEstructura = in_array($tabla, $config->tablas_solo_estructura ?? []);

                $log->update(['fase' => 'comparando', 'resumen' => array_merge($resumen, ['tabla_actual' => $tabla])]);

                $diff = $this->compararTabla($localPdo, $remotePdo, $tabla, $soloEstructura);

                if ($direccion === 'remote_to_local' || $direccion === 'bidireccional') {
                    $log->update(['fase' => 'aplicando_local']);
                    $this->aplicarCambiosLocal($localPdo, $remotePdo, $tabla, $diff, $resumen, $direccion);
                }

                if ($direccion === 'local_to_remote' || $direccion === 'bidireccional') {
                    $log->update(['fase' => 'subiendo_remoto']);
                    $this->aplicarCambiosRemoto($localPdo, $remotePdo, $tabla, $diff, $resumen);
                }

                $resumen['tablas_procesadas']++;
                $log->update(['resumen' => $resumen]);
            }

            $config->update([
                'ultima_sincronizacion' => now(),
                'ultimo_estado' => 'ok',
                'ultimo_mensaje' => 'Sincronización completada',
            ]);

            $log->update([
                'fase' => 'completado',
                'estado' => 'completado',
                'finalizado_en' => now(),
                'resumen' => $resumen,
                'detalle' => 'Sincronización ' . $direccion . ' finalizada',
            ]);

            return response()->json(['ok' => true, 'log_id' => $log->id, 'resumen' => $resumen]);

        } catch (Exception $e) {
            file_put_contents(storage_path('logs/dbsync_debug.txt'), 'SYNC ERROR: ' . $e->getMessage() . ' | LINE: ' . $e->getLine() . PHP_EOL, FILE_APPEND);
            $config->update([
                'ultima_sincronizacion' => now(),
                'ultimo_estado' => 'error',
                'ultimo_mensaje' => $e->getMessage(),
            ]);

            $log->update([
                'fase' => 'error',
                'estado' => 'error',
                'finalizado_en' => now(),
                'detalle' => $e->getMessage(),
            ]);

            return response()->json(['ok' => false, 'msg' => $e->getMessage(), 'log_id' => $log->id], 500);
        }
    }

    public function logs(DbSyncConfig $dbSyncConfig)
    {
        $config = $dbSyncConfig;
        $logs = $config->logs()->latest('iniciado_en')->paginate(20);
        return view('admin.db-sync.logs', compact('config', 'logs'));
    }

    public function downloadDiff(DbSyncConfig $dbSyncConfig)
    {
        $config = $dbSyncConfig;
        try {
            $remotePdo = $this->getRemotePdo($config);
            $localPdo = DB::connection()->getPdo();

            $tablas = $this->getTablasComunes($localPdo, $remotePdo, $config);
            $sql = "-- Diff generado " . now()->format('Y-m-d H:i:s') . "\n\n";

            foreach ($tablas as $tabla) {
                if (in_array($tabla, $config->tablas_excluir ?? [])) continue;
                $diff = $this->compararTabla($localPdo, $remotePdo, $tabla, false);
                if (!empty($diff['inserts']) || !empty($diff['updates']) || !empty($diff['deletes'])) {
                    $sql .= "-- Tabla: {$tabla}\n";
                    foreach ($diff['inserts'] as $ins) $sql .= $ins . ";\n";
                    foreach ($diff['updates'] as $upd) $sql .= $upd . ";\n";
                    foreach ($diff['deletes'] as $del) $sql .= $del . ";\n";
                    $sql .= "\n";
                }
            }

            return response($sql)
                ->header('Content-Type', 'text/sql')
                ->header('Content-Disposition', 'attachment; filename="diff_' . $config->nombre . '_' . now()->format('Ymd_His') . '.sql"');
        } catch (Exception $e) {
            return response()->json(['ok' => false, 'msg' => $e->getMessage()], 500);
        }
    }

    // ==================== HELPERS ====================

    private function getRemotePdo(DbSyncConfig $config): \PDO
    {
        $cfg = $config->getConnectionConfig();
        $dsn = "mysql:host={$cfg['host']};port={$cfg['port']};dbname={$cfg['database']};charset={$cfg['charset']}";
        return new \PDO($dsn, $cfg['username'], $cfg['password'], [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ]);
    }

    private function getTablasComunes(\PDO $local, \PDO $remote, DbSyncConfig $config): array
    {
        $localTables = $local->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);
        $remoteTables = $remote->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);
        return array_values(array_intersect($localTables, $remoteTables));
    }

    private function compararTabla(\PDO $local, \PDO $remote, string $tabla, bool $soloEstructura): array
    {
        $diff = ['inserts' => [], 'updates' => [], 'deletes' => [], 'schema_changes' => []];

        // Comparar estructura
        $localCols = $this->getColumnas($local, $tabla);
        $remoteCols = $this->getColumnas($remote, $tabla);

        if ($localCols !== $remoteCols) {
            $diff['schema_changes'] = [
                'local' => $localCols,
                'remote' => $remoteCols,
            ];
        }

        if ($soloEstructura) {
            return $diff;
        }

        // Obtener PK
        $pk = $this->getPrimaryKey($local, $tabla);
        if (!$pk) {
            $pk = $this->getPrimaryKey($remote, $tabla);
        }
        if (!$pk) {
            $pk = 'id'; // fallback
        }

        // Comparar datos (por PK)
        $localRows = $local->query("SELECT * FROM `{$tabla}`")->fetchAll(\PDO::FETCH_ASSOC);
        $remoteRows = $remote->query("SELECT * FROM `{$tabla}`")->fetchAll(\PDO::FETCH_ASSOC);

        $localMap = [];
        foreach ($localRows as $r) $localMap[$r[$pk]] = $r;
        $remoteMap = [];
        foreach ($remoteRows as $r) $remoteMap[$r[$pk]] = $r;

        $allKeys = array_unique(array_merge(array_keys($localMap), array_keys($remoteMap)), SORT_REGULAR);

        foreach ($allKeys as $key) {
            $hasLocal = isset($localMap[$key]);
            $hasRemote = isset($remoteMap[$key]);

            if ($hasRemote && !$hasLocal) {
                // Existe en remoto, no en local → INSERT local
                $cols = implode('`, `', array_keys($remoteMap[$key]));
                $vals = implode(', ', array_map(fn($v) => $v === null ? 'NULL' : $local->quote($v), $remoteMap[$key]));
                $diff['inserts'][] = "INSERT INTO `{$tabla}` (`{$cols}`) VALUES ({$vals})";
            } elseif ($hasLocal && !$hasRemote) {
                // Existe en local, no en remoto → DELETE local (o INSERT remoto según dirección)
                $diff['deletes'][] = "DELETE FROM `{$tabla}` WHERE `{$pk}` = " . $local->quote($key);
            } elseif ($hasLocal && $hasRemote) {
                // Existe en ambos → comparar columnas
                if ($localMap[$key] !== $remoteMap[$key]) {
                    $sets = [];
                    foreach ($remoteMap[$key] as $col => $val) {
                        if (!isset($localMap[$key][$col]) || $localMap[$key][$col] !== $val) {
                            $sets[] = "`{$col}` = " . ($val === null ? 'NULL' : $local->quote($val));
                        }
                    }
                    if ($sets) {
                        $diff['updates'][] = "UPDATE `{$tabla}` SET " . implode(', ', $sets) . " WHERE `{$pk}` = " . $local->quote($key);
                    }
                }
            }
        }

        return $diff;
    }

    private function getColumnas(\PDO $pdo, string $tabla): array
    {
        $stmt = $pdo->query("DESCRIBE `{$tabla}`");
        $cols = [];
        while ($row = $stmt->fetch()) {
            $cols[$row['Field']] = [
                'type' => $row['Type'],
                'null' => $row['Null'],
                'key' => $row['Key'],
                'default' => $row['Default'],
                'extra' => $row['Extra'],
            ];
        }
        return $cols;
    }

    private function getPrimaryKey(\PDO $pdo, string $tabla): ?string
    {
        $stmt = $pdo->query("SHOW KEYS FROM `{$tabla}` WHERE Key_name = 'PRIMARY'");
        $row = $stmt->fetch();
        return $row ? $row['Column_name'] : null;
    }

    private function aplicarCambiosLocal(\PDO $local, \PDO $remote, string $tabla, array $diff, array &$resumen, string $direccion = 'bidireccional'): void
    {
        $local->beginTransaction();
        try {
            foreach ($diff['inserts'] as $sql) {
                $local->exec($sql);
                $resumen['filas_insertadas']++;
            }
            foreach ($diff['updates'] as $sql) {
                $local->exec($sql);
                $resumen['filas_actualizadas']++;
            }
            // SEGURIDAD: nunca borrar filas del lado local desde este sync.
            // Las PKs de local y remoto NO son equivalentes (cada BD tiene su
            // propio AUTO_INCREMENT), por lo que los "deletes" detectados aquí
            // son filas locales únicas que NO deben eliminarse. Un borrado real
            // solo debe hacerse de forma explícita y manual.
            $local->commit();
        } catch (Exception $e) {
            $local->rollBack();
            $resumen['errores'][] = "Tabla {$tabla}: " . $e->getMessage();
        }
    }

    private function aplicarCambiosRemoto(\PDO $local, \PDO $remote, string $tabla, array $diff, array &$resumen): void
    {
        // Inverso: lo que está en local y no en remoto → INSERT remoto
        // Lo que está en remoto y no en local → DELETE remoto
        // Diferencias → UPDATE remoto con valores de local

        $pk = $this->getPrimaryKey($local, $tabla);
        if (!$pk) return;

        $localRows = $local->query("SELECT * FROM `{$tabla}`")->fetchAll(\PDO::FETCH_ASSOC);
        $remoteRows = $remote->query("SELECT * FROM `{$tabla}`")->fetchAll(\PDO::FETCH_ASSOC);

        $localMap = [];
        foreach ($localRows as $r) $localMap[$r[$pk]] = $r;
        $remoteMap = [];
        foreach ($remoteRows as $r) $remoteMap[$r[$pk]] = $r;

        $allKeys = array_unique(array_merge(array_keys($localMap), array_keys($remoteMap)), SORT_REGULAR);

        $remote->beginTransaction();
        try {
            foreach ($allKeys as $key) {
                $hasLocal = isset($localMap[$key]);
                $hasRemote = isset($remoteMap[$key]);

                if ($hasLocal && !$hasRemote) {
                    // INSERT en remoto
                    $cols = implode('`, `', array_keys($localMap[$key]));
                    $vals = implode(', ', array_map(fn($v) => $v === null ? 'NULL' : $remote->quote($v), $localMap[$key]));
                    $remote->exec("INSERT INTO `{$tabla}` (`{$cols}`) VALUES ({$vals})");
                    $resumen['filas_insertadas']++;
                } elseif (!$hasLocal && $hasRemote) {
                    // SEGURIDAD: no borrar filas remotas no presentes en local.
                    // Las PKs locales y remotas no son equivalentes; estos registros
                    // remotos son únicos del servidor y no deben eliminarse.
                    $resumen['filas_eliminadas']++;
                } elseif ($hasLocal && $hasRemote && $localMap[$key] !== $remoteMap[$key]) {
                    // UPDATE remoto con valores de local
                    $sets = [];
                    foreach ($localMap[$key] as $col => $val) {
                        if ($col === $pk) continue;
                        if (!isset($remoteMap[$key][$col]) || $remoteMap[$key][$col] !== $val) {
                            $sets[] = "`{$col}` = " . ($val === null ? 'NULL' : $remote->quote($val));
                        }
                    }
                    if ($sets) {
                        $remote->exec("UPDATE `{$tabla}` SET " . implode(', ', $sets) . " WHERE `{$pk}` = " . $remote->quote($key));
                        $resumen['filas_actualizadas']++;
                    }
                }
            }
            $remote->commit();
        } catch (Exception $e) {
            $remote->rollBack();
            $resumen['errores'][] = "Tabla {$tabla} (remoto): " . $e->getMessage();
        }
    }
}