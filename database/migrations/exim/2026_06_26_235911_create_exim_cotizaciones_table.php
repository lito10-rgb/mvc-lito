<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exim_cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 50)->unique();
            $table->foreignId('cliente_id')->constrained('exim_clientes')->cascadeOnDelete();
            $table->date('fecha')->useCurrent();
            $table->integer('validez_dias')->default(30);
            $table->foreignId('incoterm_id')->nullable()->constrained('exim_incoterms')->nullOnDelete();
            $table->foreignId('transporte_id')->nullable()->constrained('exim_transportes')->nullOnDelete();
            $table->foreignId('contenedor_id')->nullable()->constrained('exim_contenedores')->nullOnDelete();
            $table->foreignId('moneda_id')->nullable()->constrained('exim_monedas')->nullOnDelete();
            $table->decimal('tipo_cambio', 12, 4)->default(1);
            $table->decimal('gastos_operativos_total', 12, 2)->default(0);
            $table->decimal('gastos_logisticos_total', 12, 2)->default(0);
            $table->decimal('costo_pallets', 12, 2)->default(0);
            $table->decimal('costo_contenedor', 12, 2)->default(0);
            $table->decimal('seguro_total', 12, 2)->default(0);
            $table->decimal('documentacion_total', 12, 2)->default(0);
            $table->decimal('utilidad_porcentaje', 5, 2)->default(10);
            $table->decimal('utilidad_monto', 12, 2)->default(0);
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('total', 14, 2)->default(0);
            $table->text('notas')->nullable();
            $table->enum('estado', ['borrador', 'enviada', 'aprobada', 'rechazada'])->default('borrador');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exim_cotizaciones');
    }
};
