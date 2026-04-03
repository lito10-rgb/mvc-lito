<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cabeceras', function (Blueprint $table) {
            // $table->foreignId('producto_id')->nullable()->constrained('productos')->onDelete('set null');
            // $table->foreignId('categoria_id')->nullable()->constrained('categorias')->onDelete('set null');
            // $table->foreignId('subcategoria_id')->nullable()->constrained('subcategorias')->onDelete('set null');
            // $table->foreignId('marca_id')->nullable()->constrained('marcas')->onDelete('set null');
            // $table->foreignId('manual_id')->nullable()->constrained('manuales')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cabeceras', function (Blueprint $table) {
            //
        $table->dropForeign(['producto_id']);
            $table->dropForeign(['categoria_id']);
            $table->dropForeign(['subcategoria_id']);
            $table->dropForeign(['marca_id']);
            $table->dropForeign(['manual_id']);

            $table->dropColumn(['producto_id', 'categoria_id', 'subcategoria_id', 'marca_id', 'manual_id']);
        });
    }
};
