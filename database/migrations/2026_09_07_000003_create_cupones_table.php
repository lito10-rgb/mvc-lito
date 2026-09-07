<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cupones', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 50)->unique();
            $table->enum('tipo', ['porcentaje', 'monto_fijo'])->default('porcentaje');
            $table->decimal('valor', 10, 2);
            $table->decimal('min_compra', 10, 2)->default(0);
            $table->unsignedInteger('max_usos')->nullable();
            $table->unsignedInteger('usos_actuales')->default(0);
            $table->unsignedBigInteger('negocio_id')->nullable();
            $table->dateTime('fecha_inicio')->nullable();
            $table->dateTime('fecha_fin')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('negocio_id')->references('id')->on('negocios')->nullOnDelete();
            $table->index('codigo');
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cupones');
    }
};
