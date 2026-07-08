<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exim_incoterms', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 3)->unique();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->boolean('incluye_transporte_interno')->default(false);
            $table->boolean('incluye_flete_maritimo')->default(false);
            $table->boolean('incluye_seguro')->default(false);
            $table->boolean('incluye_aduanas_origen')->default(false);
            $table->boolean('incluye_aduanas_destino')->default(false);
            $table->boolean('incluye_transporte_destino')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exim_incoterms');
    }
};
