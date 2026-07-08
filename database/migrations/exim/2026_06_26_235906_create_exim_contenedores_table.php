<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exim_contenedores', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['20ft', '40ft', '40hq']);
            $table->decimal('largo_cm', 8, 2);
            $table->decimal('ancho_cm', 8, 2);
            $table->decimal('alto_cm', 8, 2);
            $table->decimal('capacidad_max_kg', 10, 2);
            $table->integer('pallets_max');
            $table->integer('sacos_max')->nullable();
            $table->decimal('flete_maritimo', 12, 2)->default(0);
            $table->decimal('seguro', 12, 2)->default(0);
            $table->decimal('gastos_portuarios', 12, 2)->default(0);
            $table->decimal('documentacion', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exim_contenedores');
    }
};
