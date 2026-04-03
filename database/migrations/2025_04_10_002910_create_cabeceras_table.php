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
        Schema::create('cabeceras', function (Blueprint $table) {
            $table->id();
           $table->text('ruta');
            $table->text('titulo');
            $table->text('descripcion');
            $table->text('palabrasClaves');
            $table->text('portada');
            $table->timestamp('fecha')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabeceras');
    }
};
