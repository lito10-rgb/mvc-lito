<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Marca;
use App\Models\Producto;

class MigrarCafePeruano extends Command
{
    protected $signature = 'migrar:cafe-peruano
                            {--negocio= : Set negocio/origin domain for imported users}
                            {--usuarios : Only migrate users}
                            {--productos : Only migrate products}
                            {--dry-run : Show what would be done without inserting}';
    protected $description = 'Migrate categorias, subcategorias, marcas, usuarios, productos from cafeperu_temp to master';

    protected $marcasMap = [];
    protected $categoriasMap = [];
    protected $subcategoriasMap = [];

    public function handle()
    {
        DB::statement("SET SQL_MODE=''");
        DB::connection('cafeperu')->statement("SET SQL_MODE=''");

        $onlyUsuarios = $this->option('usuarios');
        $onlyProductos = $this->option('productos');
        $dryRun = $this->option('dry-run');

        if (!$onlyUsuarios && !$onlyProductos) {
            $this->migrateMarcas($dryRun);
            $this->migrateCategorias($dryRun);
            $this->migrateSubcategorias($dryRun);
            $this->migrateUsuarios($dryRun);
            $this->migrateProductos($dryRun);
        } elseif ($onlyUsuarios) {
            $this->migrateUsuarios($dryRun);
        } elseif ($onlyProductos) {
            $this->migrateProductos($dryRun);
        }

        $this->info('Migration completed!');
    }

    protected function sanitizeDate($val)
    {
        if (!$val || $val === '0000-00-00 00:00:00' || $val === '0000-00-00') {
            return '0000-00-00 00:00:00';
        }
        return $val;
    }

    protected function sanitizeInt($val)
    {
        return $val === '' || $val === null ? 0 : (int) $val;
    }

    protected function sanitizeFloat($val)
    {
        return $val === '' || $val === null ? 0.0 : (float) $val;
    }

    protected function sanitizeString($val)
    {
        return $val === null ? '' : $val;
    }

    protected function migrateMarcas(bool $dryRun = false)
    {
        $cafeMarcas = DB::connection('cafeperu')->table('marcas')->get();
        $bar = $this->output->createProgressBar($cafeMarcas->count());
        $bar->start();

        foreach ($cafeMarcas as $marca) {
            $existing = Marca::where('ruta', $marca->ruta)->first();
            if ($existing) {
                $this->marcasMap[$marca->id] = $existing->id;
                $bar->advance();
                continue;
            }

            if ($dryRun) {
                $this->marcasMap[$marca->id] = null;
                $bar->advance();
                continue;
            }

            $new = Marca::create([
                'nombre' => $this->sanitizeString($marca->nombre),
                'ruta' => $this->sanitizeString($marca->ruta),
                'descripcion' => $this->sanitizeString($marca->descripcion),
                'detalle' => $this->sanitizeString($marca->detalle),
                'fecha' => $this->sanitizeDate($marca->fecha),
                'estado' => $this->sanitizeInt($marca->estado),
                'imgMarca' => $this->sanitizeString($marca->imgMarca),
            ]);
            $this->marcasMap[$marca->id] = $new->id;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Marcas: ' . count($this->marcasMap) . ' mapped');
    }

    protected function migrateCategorias(bool $dryRun = false)
    {
        $cafeCategorias = DB::connection('cafeperu')->table('categorias')->get();
        $bar = $this->output->createProgressBar($cafeCategorias->count());
        $bar->start();

        foreach ($cafeCategorias as $cat) {
            $existing = Categoria::where('ruta', $cat->ruta)->first();
            if ($existing) {
                $this->categoriasMap[$cat->id] = $existing->id;
                $bar->advance();
                continue;
            }

            if ($dryRun) {
                $this->categoriasMap[$cat->id] = null;
                $bar->advance();
                continue;
            }

            $new = Categoria::create([
                'nombre' => $this->sanitizeString($cat->categoria),
                'ruta' => $this->sanitizeString($cat->ruta),
                'estado' => $this->sanitizeInt($cat->estado),
                'oferta' => $this->sanitizeInt($cat->oferta),
                'precioOferta' => $this->sanitizeFloat($cat->precioOferta),
                'descuentoOferta' => $this->sanitizeInt($cat->descuentoOferta),
                'imgOferta' => $this->sanitizeString($cat->imgOferta),
                'finOferta' => $this->sanitizeDate($cat->finOferta),
                'fecha' => $this->sanitizeDate($cat->fecha),
            ]);
            $this->categoriasMap[$cat->id] = $new->id;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Categorias: ' . count($this->categoriasMap) . ' mapped');
    }

    protected function migrateSubcategorias(bool $dryRun = false)
    {
        $subs = DB::connection('cafeperu')->table('subcategorias')->get();
        $bar = $this->output->createProgressBar($subs->count());
        $bar->start();

        foreach ($subs as $sub) {
            $existing = Subcategoria::where('ruta', $sub->ruta)->first();
            if ($existing) {
                $this->subcategoriasMap[$sub->id] = $existing->id;
                $bar->advance();
                continue;
            }

            if (!$sub->id_categoria || !isset($this->categoriasMap[$sub->id_categoria])) {
                if ($sub->id_categoria && !$this->categoriasMap[$sub->id_categoria]) {
                    $this->warn("Subcategoria '{$sub->subcategoria}' (ruta: {$sub->ruta}) has unmapped categoria_id {$sub->id_categoria}, skipping");
                }
                $bar->advance();
                continue;
            }
            $newCategoriaId = $this->categoriasMap[$sub->id_categoria];

            if ($dryRun) {
                $this->subcategoriasMap[$sub->id] = null;
                $bar->advance();
                continue;
            }

            $new = Subcategoria::create([
                'subcategoria' => $this->sanitizeString($sub->subcategoria),
                'id_categoria' => $newCategoriaId,
                'ruta' => $this->sanitizeString($sub->ruta),
                'estado' => $this->sanitizeInt($sub->estado),
                'ofertadoPorCategoria' => $this->sanitizeInt($sub->ofertadoPorCategoria),
                'oferta' => $this->sanitizeInt($sub->oferta),
                'precioOferta' => $this->sanitizeFloat($sub->precioOferta),
                'descuentoOferta' => $this->sanitizeInt($sub->descuentoOferta),
                'imgOferta' => $this->sanitizeString($sub->imgOferta),
                'finOferta' => $this->sanitizeDate($sub->finOferta),
                'fecha' => $this->sanitizeDate($sub->fecha),
                'detalle' => $this->sanitizeString($sub->detalle),
            ]);
            $this->subcategoriasMap[$sub->id] = $new->id;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Subcategorias: ' . count($this->subcategoriasMap) . ' mapped');
    }

    protected function migrateUsuarios(bool $dryRun = false)
    {
        $usuarios = DB::connection('cafeperu')->table('usuarios')->orderBy('id')->get();
        $total = $usuarios->count();
        $inserted = 0;
        $skipped = 0;
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($usuarios as $u) {
            $email = trim($u->email ?? '');
            if ($email !== '' && User::where('email', $email)->exists()) {
                $skipped++;
                $bar->advance();
                continue;
            }

            if ($dryRun) {
                $inserted++;
                $bar->advance();
                continue;
            }

            User::create([
                'nombre' => $this->sanitizeString($u->nombre),
                'password' => $this->sanitizeString($u->password),
                'email' => $email,
                'modo' => $this->sanitizeString($u->modo),
                'foto' => $this->sanitizeString($u->foto),
                'verificacion' => $this->sanitizeInt($u->verificacion),
                'emailEncriptado' => $this->sanitizeString($u->emailEncriptado),
                'fecha' => $this->sanitizeDate($u->fecha),
                'negocio' => $this->option('negocio'),
            ]);
            $inserted++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Usuarios: {$inserted} inserted, {$skipped} skipped (email exists)");
    }

    protected function migrateProductos(bool $dryRun = false)
    {
        $productos = DB::connection('cafeperu')->table('productos')->orderBy('id')->get();
        $bar = $this->output->createProgressBar($productos->count());
        $bar->start();
        $inserted = 0;
        $skipped = 0;

        foreach ($productos as $p) {
            $existing = Producto::where('ruta', $p->ruta)->first();
            if ($existing) {
                $skipped++;
                $bar->advance();
                continue;
            }

            $categoriaId = $this->categoriasMap[$p->id_categoria] ?? null;
            $subcategoriaId = $this->subcategoriasMap[$p->id_subcategoria] ?? null;
            $marcaId = $this->marcasMap[$p->id_marca] ?? null;

            if ($dryRun) {
                $inserted++;
                $bar->advance();
                continue;
            }

            Producto::create([
                'tipo' => $this->sanitizeString($p->tipo),
                'ruta' => $this->sanitizeString($p->ruta),
                'estado' => $this->sanitizeInt($p->estado),
                'titulo' => $this->sanitizeString($p->titulo),
                'titular' => $this->sanitizeString($p->titular),
                'descripcion' => $this->sanitizeString($p->descripcion),
                'multimedia' => $this->sanitizeString($p->multimedia),
                'detalles' => $this->sanitizeString($p->detalles),
                'precio' => $this->sanitizeFloat($p->precio),
                'stock' => 0,
                'portada' => $this->sanitizeString($p->portada),
                'vistas' => $this->sanitizeInt($p->vistas),
                'ventas' => $this->sanitizeInt($p->ventas),
                'vistasGratis' => $this->sanitizeInt($p->vistasGratis),
                'ventasGratis' => $this->sanitizeInt($p->ventasGratis),
                'ofertadoPorCategoria' => $this->sanitizeInt($p->ofertadoPorCategoria),
                'ofertadoPorSubCategoria' => $this->sanitizeInt($p->ofertadoPorSubCategoria),
                'oferta' => $this->sanitizeInt($p->oferta),
                'precioOferta' => $this->sanitizeFloat($p->precioOferta),
                'descuentoOferta' => $this->sanitizeInt($p->descuentoOferta),
                'imgOferta' => $this->sanitizeString($p->imgOferta),
                'finOferta' => $this->sanitizeDate($p->finOferta),
                'peso' => $this->sanitizeFloat($p->peso),
                'entrega' => $this->sanitizeFloat($p->entrega),
                'categoria_id' => $categoriaId,
                'subcategoria_id' => $subcategoriaId,
                'marca_id' => $marcaId,
                'fecha' => $this->sanitizeDate($p->fecha),
            ]);
            $inserted++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Productos: {$inserted} inserted, {$skipped} skipped (ruta exists)");
    }
}
