<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('user_profiles')) {
            Schema::create('user_profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('email')->nullable();
                $table->string('email2')->nullable();
                $table->string('email3')->nullable();
                $table->string('email4')->nullable();
                $table->string('empresa')->nullable();
                $table->string('cargo')->nullable();
                $table->string('tipo_documento')->nullable();
                $table->string('num_documento')->nullable();
                $table->string('telefono')->nullable();
                $table->string('celular')->nullable();
                $table->string('celular2')->nullable();
                $table->string('celular3')->nullable();
                $table->string('celular4')->nullable();
                $table->string('whatsapp')->nullable();
                $table->string('skype')->nullable();
                $table->string('wechat')->nullable();
                $table->date('fechanacimiento')->nullable();
                $table->string('pais')->nullable();
                $table->string('estado')->nullable();
                $table->string('provincia')->nullable();
                $table->string('distrito')->nullable();
                $table->string('direccion')->nullable();
                $table->string('codigopostal')->nullable();
                $table->text('detalle')->nullable();
                $table->string('web')->nullable();
                $table->string('web2')->nullable();
                $table->string('facebook')->nullable();
                $table->string('instagram')->nullable();
                $table->string('twitter')->nullable();
                $table->string('pinterest')->nullable();
                $table->string('alibaba')->nullable();
                $table->string('madeinchina')->nullable();
                $table->string('categoria')->nullable();
                $table->string('subcategoria')->nullable();
                $table->string('tipo_usuario_vendedor_productor')->nullable();
                $table->string('codigo')->nullable();
                $table->string('cuenta_banco')->nullable();
                $table->string('representantelegal')->nullable();
                $table->timestamp('fecha_registro')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
