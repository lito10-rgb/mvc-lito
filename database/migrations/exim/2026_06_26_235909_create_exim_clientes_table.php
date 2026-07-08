<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exim_clientes', function (Blueprint $table) {
            $table->id();
            $table->string('empresa', 255);
            $table->string('contacto', 255);
            $table->string('cargo', 255)->nullable();
            $table->unsignedInteger('pais_id')->nullable()->index();
            $table->string('ciudad', 255)->nullable();
            $table->text('direccion')->nullable();
            $table->string('email', 255);
            $table->string('whatsapp', 50)->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('idioma', 50)->nullable();
            $table->string('moneda_preferida', 3)->nullable();
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exim_clientes');
    }
};
