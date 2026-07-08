<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exim_productos', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['cafe_verde', 'cafe_tostado', 'cafe_molido', 'maquinaria', 'equipo_industrial', 'repuesto', 'accesorio']);
            $table->foreignId('producto_id')->nullable()->constrained('productos')->nullOnDelete();
            $table->string('nombre', 255);
            $table->json('atributos')->nullable();
            $table->decimal('precio_base', 12, 2)->default(0);
            $table->foreignId('moneda_id')->nullable()->constrained('exim_monedas')->nullOnDelete();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exim_productos');
    }
};
