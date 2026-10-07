<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('permisos')) {
            Schema::create('permisos', function (Blueprint $table) {
                $table->id();
                $table->string('clave')->unique();
                $table->string('etiqueta');
                $table->string('modulo');
                $table->string('descripcion')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('permiso_role')) {
            Schema::create('permiso_role', function (Blueprint $table) {
                $table->id();
                $table->integer('permiso_id');
                $table->integer('role_id');
                $table->index(['role_id', 'permiso_id']);
            });
        }

        if (!Schema::hasTable('permiso_user')) {
            Schema::create('permiso_user', function (Blueprint $table) {
                $table->id();
                $table->integer('permiso_id');
                $table->integer('user_id');
                $table->index(['user_id', 'permiso_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('permiso_user');
        Schema::dropIfExists('permiso_role');
        Schema::dropIfExists('permisos');
    }
};