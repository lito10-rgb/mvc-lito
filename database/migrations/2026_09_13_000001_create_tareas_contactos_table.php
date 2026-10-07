<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tareas_contactos')) {
            Schema::create('tareas_contactos', function (Blueprint $table) {
                $table->id();
                $table->string('cuenta')->nullable();                 // cuenta Outlook desde la que se respondió
                $table->string('proveedor_email')->nullable();        // email del proveedor contactado
                $table->string('proveedor_nombre')->nullable();
                $table->unsignedBigInteger('negocio_id')->nullable(); // negocio usado en el mensaje
                $table->unsignedBigInteger('idx')->nullable();        // idx del correo original en la sesión
                $table->string('asunto')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tareas_contactos');
    }
};