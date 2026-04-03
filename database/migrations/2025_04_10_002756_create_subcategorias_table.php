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
            Schema::create('subcategorias', function (Blueprint $table) {
       $table->id();
            $table->text('subcategoria');
            $table->unsignedBigInteger('id_categoria');
            $table->text('ruta');
            $table->integer('estado');
            $table->integer('ofertadoPorCategoria');
            $table->integer('oferta');
            $table->float('precioOferta');
            $table->integer('descuentoOferta');
            $table->text('imgOferta');
            $table->dateTime('finOferta');
            $table->timestamp('fecha')->useCurrent()->useCurrentOnUpdate();
            $table->text('detalle');

            $table->foreign('id_categoria')->references('id')->on('categorias')->onDelete('cascade');
            
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcategorias');
    }
};
