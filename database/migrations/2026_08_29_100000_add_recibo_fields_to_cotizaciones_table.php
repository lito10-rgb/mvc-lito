<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->unsignedInteger('recibo_numero')->nullable()->after('imagen_referencia');
            $table->timestamp('recibo_fecha')->nullable()->after('recibo_numero');
            $table->string('recibo_metodo_pago', 100)->nullable()->after('recibo_fecha');
            $table->string('recibo_recibido_por', 255)->nullable()->after('recibo_metodo_pago');
            $table->string('recibo_pagado_por', 255)->nullable()->after('recibo_recibido_por');
            $table->text('recibo_observaciones')->nullable()->after('recibo_pagado_por');
        });
    }

    public function down(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->dropColumn([
                'recibo_numero',
                'recibo_fecha',
                'recibo_metodo_pago',
                'recibo_recibido_por',
                'recibo_pagado_por',
                'recibo_observaciones',
            ]);
        });
    }
};
