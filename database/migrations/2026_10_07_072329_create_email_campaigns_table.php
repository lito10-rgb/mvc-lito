<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // ej. "Aviso contraseña Oct 2026"
            $table->string('tipo'); // bulk, aviso_password
            $table->string('negocio')->nullable();
            $table->string('batch_id')->unique(); // vincula con email_logs
            $table->unsignedInteger('total_enviados')->default(0);
            $table->unsignedInteger('total_fallidos')->default(0);
            $table->text('notas')->nullable();
            $table->unsignedBigInteger('creado_por')->nullable(); // user id
            $table->timestamp('enviado_en')->useCurrent();
            $table->index(['tipo', 'enviado_en']);
            $table->index('creado_por');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_campaigns');
    }
};