<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categoria_negocio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->foreignId('negocio_id')->constrained()->onDelete('cascade');
            $table->unique(['categoria_id', 'negocio_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categoria_negocio');
    }
};
