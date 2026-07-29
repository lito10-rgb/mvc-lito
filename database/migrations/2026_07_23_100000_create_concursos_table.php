<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('concursos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->date('fecha_sorteo');
            $table->string('premio')->nullable();
            $table->enum('estado', ['borrador', 'activo', 'finalizado'])->default('borrador');
            $table->unsignedBigInteger('ganador_participante_id')->nullable();
            $table->timestamps();
        });

        Schema::create('concurso_participantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('concurso_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('codigo', 8)->unique();
            $table->boolean('email_enviado')->default(false);
            $table->boolean('ganador')->default(false);
            $table->timestamps();
        });

        Schema::table('concursos', function (Blueprint $table) {
            $table->foreign('ganador_participante_id')->references('id')->on('concurso_participantes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('concursos');
        Schema::dropIfExists('concurso_participantes');
    }
};
