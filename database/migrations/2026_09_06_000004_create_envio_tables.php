<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_envio', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->string('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        Schema::create('tarifas_envio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_envio_id')->constrained('tipos_envio')->cascadeOnDelete();
            $table->unsignedBigInteger('categoria_id')->nullable();
            $table->decimal('minimo', 12, 2)->nullable()->default(0);
            $table->decimal('maximo', 12, 2)->nullable();
            $table->decimal('costo', 12, 2);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('categoria_id')->references('id')->on('categorias')->nullOnDelete();
            $table->index(['tipo_envio_id', 'categoria_id', 'activo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarifas_envio');
        Schema::dropIfExists('tipos_envio');
    }
};