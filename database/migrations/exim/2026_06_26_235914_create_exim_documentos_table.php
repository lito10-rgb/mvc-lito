<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exim_documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cotizacion_id')->constrained('exim_cotizaciones')->cascadeOnDelete();
            $table->enum('tipo', ['quotation', 'proforma_invoice', 'commercial_invoice', 'packing_list', 'export_cost_sheet']);
            $table->string('numero_documento', 100)->nullable();
            $table->string('archivo', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exim_documentos');
    }
};
