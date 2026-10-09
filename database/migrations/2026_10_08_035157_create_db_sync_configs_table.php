<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('db_sync_configs', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique(); // ej: 'cafe-peruano-prod'
            $table->string('host');
            $table->unsignedInteger('puerto')->default(3306);
            $table->string('database');
            $table->string('usuario');
            $table->string('password_enc'); // encriptado
            $table->boolean('activo')->default(true);
            $table->json('tablas_excluir')->nullable(); // tablas a ignorar en sync
            $table->json('tablas_solo_estructura')->nullable(); // solo comparar estructura
            $table->timestamp('ultima_sincronizacion')->nullable();
            $table->enum('ultimo_estado', ['ok','error','pendiente'])->nullable();
            $table->text('ultimo_mensaje')->nullable();
            $table->timestamps();
        });

        Schema::create('db_sync_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('db_sync_config_id')->constrained()->cascadeOnDelete();
            $table->enum('direccion', ['remote_to_local','local_to_remote','bidireccional']);
            $table->enum('fase', ['comparando','descargando','aplicando_local','subiendo_remoto','completado','error']);
            $table->json('resumen')->nullable(); // {tablas_afectadas, filas_insertadas, filas_actualizadas, errores}
            $table->text('detalle')->nullable();
            $table->enum('estado', ['ejecutando','completado','error','cancelado'])->default('ejecutando');
            $table->unsignedBigInteger('ejecutado_por')->nullable(); // user_id
            $table->timestamp('iniciado_en')->useCurrent();
            $table->timestamp('finalizado_en')->nullable();
            $table->index(['db_sync_config_id', 'iniciado_en']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('db_sync_logs');
        Schema::dropIfExists('db_sync_configs');
    }
};