<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subcategoria_negocio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subcategoria_id')->constrained('subcategorias')->onDelete('cascade');
            $table->foreignId('negocio_id')->constrained()->onDelete('cascade');
            $table->unique(['subcategoria_id', 'negocio_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subcategoria_negocio');
    }
};
