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
            Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->text('categoria');
            $table->text('ruta');
            $table->integer('estado');
            $table->integer('oferta');
            $table->float('precioOferta');
            $table->integer('descuentoOferta');
            $table->text('imgOferta');
            $table->dateTime('finOferta');
            $table->timestamp('fecha')->useCurrent()->useCurrentOnUpdate();
             $table->timestamps(); // Agregar created_at y updated_at
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};