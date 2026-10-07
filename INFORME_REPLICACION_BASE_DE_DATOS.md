# INFORME: SISTEMA DE REPLICACIÓN DE BASE DE DATOS Y SINCRONIZACIÓN

## 📋 RESUMEN EJECUTIVO

**Objetivo:** Establecer un sistema robusto para replicación bidireccional de base de datos y sincronización de cambios entre local y remoto.

**Estado Actual:** Proyecto Laravel 12 con MySQL/MariaDB, múltiples migraciones existentes, sin sistema de replicación formal.

**Recomendación:** Implementar sistema híbrido con migraciones Laravel para estructura + sincronización selectiva para datos.

---

## 🔍 ANÁLISIS DEL ESTADO ACTUAL

### Infraestructura Actual:
- **Local:** XAMPP (MySQL/MariaDB) - `mqyeq`
- **Remoto:** cPanel (cafe-peruano.com, equiposymaquinas.com)
- **Framework:** Laravel 12
- **Migraciones:** 84 archivos de migración existentes
- **Conexiones:** MySQL, SQLite, MariaDB configuradas

### Problemas Identificados:
1. ❌ **Sin sistema de replicación formal** - Export manual actual
2. ❌ **Riesgo de sobrescritura de datos** - Export completo vs selectivo
3. ❌ **Sin control de versiones de estructura** - Cambios de esquema no rastreados
4. ❌ **Sin automatización** - Proceso manual propenso a errores
5. ❌ **Conflictos potenciales** - Cambos simultáneos local/remoto

---

## 🎯 ESTRATEGIA DE REPLICACIÓN RECOMENDADA

### MODELO HÍBRIDO: ESTRUCTURA + DATOS

#### 1. ESTRUCTURA DE BASE DE DATOS (Migraciones Laravel)
- **Uso:** Cambios en esquema de tablas, nuevas tablas, índices
- **Herramienta:** Migraciones Laravel estándar
- **Deploy:** `php artisan migrate --force` en servidor
- **Ventajas:** Versionado automático, reversible, controlado

#### 2. DATOS DE NEGOCIO (Sincronización Selectiva)
- **Uso:** Productos, categorías, clientes, cotizaciones
- **Herramienta:** Comandos personalizados + exports SQL
- **Deploy:** Import SQL manual vía phpMyAdmin
- **Ventajas:** Control granular, evitar conflictos, selectivo

#### 3. DATOS DE PRODUcción (Protegidos)
- **Uso:** Users, orders, sesiones, logs
- **Política:** No sincronizar (local ≠ remoto)
- **Ventajas:** Seguridad, integridad de datos reales

---

## 🛠️ IMPLEMENTACIÓN DETALLADA

### FASE 1: MIGRACIONES LARAVEL PARA ESTRUCTURA

#### 1.1 Crear Sistema de Migraciones Robusto

**Estado Actual:**
- ✅ 84 migraciones existentes detectadas
- ❌ Puede haber diferencias entre local y remoto

**Acción Requerida:**
```bash
# Verificar estado de migraciones local
php artisan migrate:status

# Verificar estado de migraciones remoto (vía SSH)
php artisan migrate:status
```

**Archivo Crear:** `database/migrations/[timestamp]_setup_replication_environment.php`
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabla para rastrear cambios de sincronización
        Schema::create('sync_logs', function (Blueprint $table) {
            $table->id();
            $table->string('source')->default('local'); // local, remote
            $table->string('table_name');
            $table->string('action'); // create, update, delete
            $table->json('record_ids')->nullable();
            $table->timestamp('synced_at');
            $table->boolean('success')->default(true);
            $table->text('error_message')->nullable();
            $table->timestamps();
        });

        // Tabla para controlar conflictos
        Schema::create('sync_conflicts', function (Blueprint $table) {
            $table->id();
            $table->string('table_name');
            $table->integer('record_id');
            $table->json('local_data');
            $table->json('remote_data');
            $table->enum('resolution', ['pending', 'local_wins', 'remote_wins', 'manual']);
            $table->timestamp('detected_at');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sync_conflicts');
        Schema::dropIfExists('sync_logs');
    }
};
```

#### 1.2 Flujo de Trabajo para Cambios de Estructura

**Cambios en Esquema (Nuevas tablas, columnas, índices):**

1. **Crear migración:**
```bash
php artisan make:migration agregar_campo_nuevo_tabla_productos
```

2. **Implementar cambios en migración:**
```php
public function up(): void
{
    Schema::table('productos', function (Blueprint $table) {
        $table->string('nuevo_campo')->nullable();
        $table->index('nuevo_campo');
    });
}
```

3. **Probar localmente:**
```bash
php artisan migrate
php artisan migrate:rollback --step=1  # Para probar rollback
```

4. **Generar script SQL para remoto:**
```bash
php artisan migrate:generate-sql --env=production
```

5. **Implementar en remoto:**
```bash
# Opción A: Via SSH
ssh usuario@servidor
cd /ruta/proyecto
php artisan migrate --force

# Opción B: Via phpMyAdmin (ejecutar SQL generado)
```

---

### FASE 2: SINCRONIZACIÓN DE DATOS DE NEGOCIO

#### 2.1 Clasificación de Tablas por Política de Sincronización

**TABLAS DE SÓLO LECTURA (Sincronizar Local → Remoto):**
- `categorias`, `subcategorias` (estructura de navegación)
- `marcas`, `proveedores` (catálogos maestros)
- `plantillas_correo` (configuración de sistema)

**TABLAS BIDIRECCIONALES (Sincronizar con cuidado):**
- `productos` (pueden crearse/actualizarse en ambos lados)
- `cotizaciones` (requiere reconciliación de clientes)
- `favoritos` (específicos por usuario)

**TABLAS PROTEGIDAS (No sincronizar):**
- `users` (usuarios reales de producción)
- `orders` (pedidos reales)
- `sessions` (sesiones activas)
- `migrations` (estado de migraciones)
- `sync_logs`, `sync_conflicts` (metadatos de sincronización)

#### 2.2 Comandos de Sincronización Inteligente

**Comando Crear:** `app/Console/Commands/SyncCambiosInteligente.php`

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SyncCambiosInteligente extends Command
{
    protected $signature = 'sync:inteligente {--direction=local-to-remote : Dirección de sincronización}';
    protected $description = 'Sincronización inteligente de cambios con detección de conflictos';

    public function handle()
    {
        $direction = $this->option('direction');
        $this->info("🔄 Iniciando sincronización: {$direction}");

        // Tablas bidireccionales que requieren detección de conflictos
        $bidirectionalTables = ['productos', 'cotizaciones'];

        foreach ($bidirectionalTables as $table) {
            $this->syncTableWithConflictDetection($table, $direction);
        }

        // Tablas de solo lectura (solo local → remoto)
        $readonlyTables = ['categorias', 'subcategorias', 'marcas', 'proveedores'];
        
        if ($direction === 'local-to-remote') {
            foreach ($readonlyTables as $table) {
                $this->syncTableSimple($table);
            }
        }

        $this->info("✅ Sincronización completada");
        return 0;
    }

    private function syncTableWithConflictDetection($table, $direction)
    {
        $this->info("🔍 Sincronizando {$table} con detección de conflictos...");
        
        // Implementar lógica de comparación y resolución de conflictos
        // (detalles en implementación completa)
    }

    private function syncTableSimple($table)
    {
        $this->info("📤 Sincronizando {$table} (local → remoto)...");
        // Export SQL selectivo para esta tabla
    }
}
```

#### 2.3 Export Selectivo por Timestamp

**Comando Crear:** `app/Console/Commands/ExportarCambiosRecientes.php`

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExportarCambiosRecientes extends Command
{
    protected $signature = 'export:recientes {--horas=24 : Horas desde el último sync}';
    protected $description = 'Exportar solo cambios recientes de la base de datos';

    public function handle()
    {
        $horas = $this->option('horas');
        $fechaLimite = Carbon::now()->subHours($horas);

        $this->info("📦 Exportando cambios desde: {$fechaLimite}");

        // Tablas a sincronizar
        $tablas = [
            'productos' => ['id', 'titulo', 'descripcion', 'precio', 'categoria_id', 'updated_at'],
            'categorias' => ['id', 'nombre', 'ruta', 'updated_at'],
            'subcategorias' => ['id', 'subcategoria', 'ruta', 'updated_at'],
        ];

        $exportFile = "cambios_recientes_" . date('Ymd_His') . ".sql";
        $exportPath = storage_path("app/exports/{$exportFile}");

        foreach ($tablas as $tabla => $columnas) {
            $this->exportCambiosTabla($tabla, $columnas, $fechaLimite, $exportPath);
        }

        $this->info("✅ Export completado: {$exportPath}");
        return 0;
    }

    private function exportCambiosTabla($tabla, $columnas, $fechaLimite, $exportPath)
    {
        $registros = DB::table($tabla)
            ->where('updated_at', '>=', $fechaLimite)
            ->get();

        if ($registros->isEmpty()) {
            $this->line("   ⏭️  Sin cambios en {$tabla}");
            return;
        }

        $this->line("   📝 {$registros->count()} cambios en {$tabla}");
        
        // Generar SQL INSERT statements
        // (implementación completa en archivo final)
    }
}
```

---

### FASE 3: SISTEMA DE EJECUTABLES PARA CAMBIOS DE ESTRUCTURA

#### 3.1 Generador de Scripts SQL Ejecutables

**Comando Crear:** `app/Console/Commands/GenerarScriptMigracion.php`

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerarScriptMigracion extends Command
{
    protected $signature = 'migrate:generate-script {--table= : Tabla específica}';
    protected $description = 'Generar script SQL ejecutable para cambios de estructura';

    public function handle()
    {
        $this->info("🔧 Generando script SQL para cambios de estructura...");
        
        $scriptContent = $this->generarScriptSQL();
        $scriptFile = "migracion_estructura_" . date('Ymd_His') . ".sql";
        $scriptPath = storage_path("app/exports/{$scriptFile}");
        
        file_put_contents($scriptPath, $scriptContent);
        
        $this->info("✅ Script generado: {$scriptPath}");
        $this->info("📋 Instrucciones:");
        $this->line("   1. Hacer backup de base de datos remota");
        $this->line("   2. Importar script en phpMyAdmin remoto");
        $this->line("   3. Verificar resultados");
        
        return 0;
    }

    private function generarScriptSQL()
    {
        $script = "-- Script de Migración de Estructura\n";
        $script .= "-- Generado: " . date('Y-m-d H:i:s') . "\n";
        $script .= "-- Proyecto: mvc-lito\n\n";
        
        $script .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
        
        // Analizar estructura actual vs deseada
        // Generar ALTER TABLE statements
        
        $script .= "SET FOREIGN_KEY_CHECKS=1;\n";
        
        return $script;
    }
}
```

#### 3.2 Script de Verificación Pre-Deploy

**Archivo Crear:** `scripts/verificar_migracion.php`

```php
<?php
/**
 * Script de verificación pre-deploy de migraciones
 * Ejecutar antes de aplicar cambios en producción
 */

echo "🔍 Verificando entorno de producción...\n\n";

// Verificar conexión a base de datos
try {
    $pdo = new PDO('mysql:host=localhost;dbname=cafeperu_prod', 'usuario', 'password');
    echo "✅ Conexión a base de datos exitosa\n";
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
    exit(1);
}

// Verificar espacio disponible
$espacioDisponible = disk_free_space('/');
$espacioRequerido = 100 * 1024 * 1024; // 100MB mínimo

if ($espacioDisponible < $espacioRequerido) {
    echo "❌ Espacio insuficiente en disco\n";
    exit(1);
}

echo "✅ Espacio en disco suficiente\n";

// Verificar permisos de escritura
if (!is_writable('/ruta/storage')) {
    echo "❌ Permisos insuficientes en storage\n";
    exit(1);
}

echo "✅ Permisos de escritura correctos\n\n";

echo "🎯 Verificación completada exitosamente\n";
echo "📋 Puede proceder con la migración\n";
?>
```

---

## 📋 FLUJO DE TRABAJO COMPLETO

### ESCENARIO 1: CAMBIOS EN CÓDIGO SOLAMENTE

**Flujo:**
1. **Editar código local** (vistas, controladores, modelos)
2. **Probar localmente**
3. **Commit cambios:**
```bash
git add .
git commit -m "Descripción del cambio"
git push origin master
```
4. **GitHub Actions deploy automático** → servidor remoto
5. **Limpiar caché remoto:** `https://cafe-peruano.com/deploy/clear-cache.php`

### ESCENARIO 2: CAMBIOS EN ESTRUCTURA DE BASE DE DATOS

**Flujo:**
1. **Crear migración Laravel:**
```bash
php artisan make:migration descripcion_cambio
```
2. **Implementar cambios** en archivo de migración
3. **Probar localmente:**
```bash
php artisan migrate
php artisan migrate:rollback --step=1
```
4. **Generar script SQL:**
```bash
php artisan migrate:generate-script
```
5. **Backup remoto** (vía phpMyAdmin)
6. **Ejecutar script en remoto** (phpMyAdmin o SSH)
7. **Verificar resultados**

### ESCENARIO 3: CAMBIOS EN DATOS DE NEGOCIO

**Flujo Local → Remoto:**
1. **Editar datos local** (productos, categorías, etc.)
2. **Exportar cambios recientes:**
```bash
php artisan export:recientes --horas=24
```
3. **Backup remoto**
4. **Importar SQL en remoto** (phpMyAdmin)
5. **Limpiar caché remoto**

**Flujo Remoto → Local:**
1. **Backup local**
2. **Exportar cambios desde remoto** (phpMyAdmin)
3. **Importar en local**
4. **Resolver conflictos** (si los hay)

### ESCENARIO 4: CAMBIOS SIMULTÁNEOS (CONFLICTOS)

**Flujo:**
1. **Detectar conflictos:**
```bash
php artisan sync:inteligente --direction=detect-conflicts
```
2. **Revisar reporte de conflictos**
3. **Resolver manualmente** (local wins, remote wins, o merge manual)
4. **Aplicar resolución**
5. **Documentar resolución**

---

## 🛡️ MEDIDAS DE SEGURIDAD

### 1. BACKUP OBLIGATORIO
- **Antes de cualquier import/export:** Backup completo
- **Backup automático:** Script diario (vía cron job)
- **Backup retención:** 7 días + 1 backup semanal + 1 backup mensual

### 2. VALIDACIÓN DE INTEGRIDAD
- **Checksum de archivos:** Verificar integridad de exports
- **Conteo de registros:** Verificar que import/export coincide
- **Pruebas de consistencia:** Verificar foreign keys después de import

### 3. CONTROL DE ACCESO
- **Credenciales separadas:** Local vs remoto
- **VPN/SSH:** Acceso seguro a servidor
- **Logs de sincronización:** Auditoría completa de cambios

### 4. ROLLBACK AUTOMÁTICO
- **Scripts reversibles:** Cada cambio debe tener rollback
- **Puntos de restauración:** Snapshots antes de cambios mayores
- **Alertas automáticas:** Notificación de errores en sincronización

---

## 📊 COMANDOS Y HERRAMIENTAS PROPUESTOS

### Comandos Laravel Artisan:
```bash
# Migraciones de estructura
php artisan migrate --force           # Aplicar migraciones en producción
php artisan migrate:rollback          # Revertir última migración
php artisan migrate:status            # Estado de migraciones
php artisan migrate:generate-script   # Generar script SQL

# Sincronización de datos
php artisan sync:inteligente          # Sincronización con detección de conflictos
php artisan export:recientes          # Exportar cambios recientes
php artisan sync:tabla especifica     # Sincronizar tabla específica
php artisan sync:conflictos resolver  # Resolver conflictos pendientes

# Utilidades
php artisan sync:verificar           # Verificar estado de sincronización
php artisan sync:reset               # Resetear metadatos de sincronización
```

### Scripts de Utilidad:
- `scripts/backup_db.php` - Backup automatizado
- `scripts/verificar_migracion.php` - Verificación pre-deploy
- `scripts/limpiar_sync_logs.php` - Limpieza de logs antiguos
- `scripts/analizar_conflictos.php` - Análisis detallado de conflictos

---

## 🎍 RECOMENDACIONES FINALES

### IMPLEMENTACIÓN INMEDIATA (Prioridad Alta):
1. ✅ **Configurar sistema de migraciones Laravel** para estructura
2. ✅ **Crear tablas de control** (sync_logs, sync_conflicts)
3. ✅ **Implementar export selectivo** por timestamp
4. ✅ **Establecer políticas de backup** obligatorias

### IMPLEMENTACIÓN MEDIA PLAZO (Prioridad Media):
1. 🔄 **Sistema de detección de conflictos** automático
2. 🔄 **Dashboard de monitoreo** de sincronización
3. 🔄 **Alertas automáticas** de errores
4. 🔄 **Sistema de rollback** inteligente

### IMPLEMENTACIÓN LARGO PLAZO (Prioridad Baja):
1. ⏳ **Replicación en tiempo real** (si necesario)
2. ⏳ **API de sincronización** bidireccional
3. ⏳ **Sistema de versionado de datos** completo
4. ⏳ **Machine learning** para resolución de conflictos

---

## 📞 PRÓXIMOS PASOS

1. **Revisar este informe** y confirmar enfoque
2. **Priorizar implementación** según necesidades
3. **Configurar sistema base** (migraciones + tablas de control)
4. **Probar en entorno de staging** antes de producción
5. **Documentar procesos** específicos del proyecto

---

**Generado:** 2026-08-19  
**Proyecto:** mvc-lito  
**Versión Laravel:** 12  
**Estado:** Pendiente de implementación