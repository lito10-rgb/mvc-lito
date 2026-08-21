<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pares canónicos de Zona Cafetería: [duplicado_vacío => canónico_con_productos]
     */
    protected array $duplicadosZonaCafeteria = [
        187 => 129, // Café Tostado en Grano
        188 => 130, // Café Tostado Molido
        189 => 133, // Cafeteras
        190 => 134, // Molinillos
        191 => 136, // Accesorios para Barista
    ];

    protected int $zonaCafeteriaId = 103;

    public function up(): void
    {
        Schema::create('categoria_subcategoria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categorias')->cascadeOnDelete();
            $table->foreignId('subcategoria_id')->constrained('subcategorias')->cascadeOnDelete();
            $table->unique(['categoria_id', 'subcategoria_id']);
        });

        // Backfill: cada subcategoría pertenece a su categoría primaria (id_categoria)
        $registros = DB::table('subcategorias')
            ->whereNotNull('id_categoria')
            ->get(['id', 'id_categoria'])
            ->map(fn ($s) => [
                'categoria_id' => $s->id_categoria,
                'subcategoria_id' => $s->id,
            ])
            ->all();

        DB::table('categoria_subcategoria')->insertOrIgnore($registros);

        // Mover productos de los duplicados vacíos hacia los canónicos
        foreach ($this->duplicadosZonaCafeteria as $duplicado => $canonico) {
            DB::table('productos')
                ->where('subcategoria_id', $duplicado)
                ->update(['subcategoria_id' => $canonico]);
        }

        // Compartir los canónicos con Zona Cafetería y eliminar los duplicados
        foreach ($this->duplicadosZonaCafeteria as $duplicado => $canonico) {
            DB::table('categoria_subcategoria')->insertOrIgnore([
                'categoria_id' => $this->zonaCafeteriaId,
                'subcategoria_id' => $canonico,
            ]);

            DB::table('subcategoria_negocio')->where('subcategoria_id', $duplicado)->delete();
            DB::table('subcategorias')->where('id', $duplicado)->delete();
        }
    }

    public function down(): void
    {
        // Restaurar duplicados como categorías simples no es posible sin datos,
        // solo se elimina el pivote; las subcategorías borradas quedaron movidas.
        Schema::dropIfExists('categoria_subcategoria');
    }
};
