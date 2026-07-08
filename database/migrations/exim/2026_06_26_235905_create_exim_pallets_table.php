<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exim_pallets', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['estandar', 'euro', 'industrial', 'personalizado'])->default('estandar');
            $table->enum('material', ['madera', 'plastico', 'metal'])->default('madera');
            $table->decimal('largo_cm', 8, 2);
            $table->decimal('ancho_cm', 8, 2);
            $table->decimal('alto_cm', 8, 2);
            $table->decimal('peso_kg', 8, 2);
            $table->decimal('capacidad_kg', 8, 2);
            $table->decimal('costo_unitario', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exim_pallets');
    }
};
