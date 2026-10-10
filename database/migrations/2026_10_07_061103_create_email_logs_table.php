<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('email_logs')) {
            return;
        }

        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('email');
            $table->string('nombre')->nullable();
            $table->string('asunto');
            $table->text('contenido')->nullable();
            $table->string('tipo')->default('bulk'); // bulk, aviso_password
            $table->string('negocio')->nullable(); // nombre del negocio remitente
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();
            $table->string('batch_id'); // UUID para agrupar lote
            $table->enum('estado', ['enviado', 'fallido'])->default('enviado');
            $table->text('error')->nullable();
            $table->timestamp('enviado_en')->useCurrent();
            $table->index(['batch_id', 'enviado_en']);
            $table->index(['user_id', 'enviado_en']);
            $table->index(['tipo', 'enviado_en']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};