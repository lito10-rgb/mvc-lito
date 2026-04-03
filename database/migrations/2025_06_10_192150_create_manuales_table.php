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
        // Schema::create('manuales', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        Schema::create('manuales', function (Blueprint $table) {
            $table->id();
            $table->string('ruta')->unique();
            $table->string('nombre');
            $table->date('fecha')->nullable();
            $table->boolean('estado')->default(1);
            $table->string('imgManual')->nullable();
            $table->text('detalle')->nullable();
            $table->text('descripcion')->nullable();
            
            // Relación con categoría
            $table->foreignId('id_categoria')->nullable()->constrained('categorias')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manuales');
    }
};
