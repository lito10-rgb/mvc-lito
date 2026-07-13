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
        Schema::create('producto_negocio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained()->onDelete('cascade');
            $table->foreignId('negocio_id')->constrained()->onDelete('cascade');
            $table->unique(['producto_id', 'negocio_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto_negocio');
    }
};
