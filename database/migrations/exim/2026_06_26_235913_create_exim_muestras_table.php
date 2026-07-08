<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exim_muestras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('exim_productos')->cascadeOnDelete();
            $table->foreignId('cotizacion_id')->nullable()->constrained('exim_cotizaciones')->nullOnDelete();
            $table->decimal('cantidad', 12, 2)->default(1);
            $table->decimal('peso_kg', 8, 2)->default(0);
            $table->string('tipo_empaque', 100)->nullable();
            $table->string('caja', 100)->nullable();
            $table->text('etiquetas')->nullable();
            $table->text('certificados')->nullable();
            $table->enum('courier', ['dhl', 'fedex', 'ups', 'ems', 'otro'])->nullable();
            $table->decimal('seguro', 12, 2)->default(0);
            $table->decimal('valor_muestra', 12, 2)->default(0);
            $table->decimal('costo_envio', 12, 2)->default(0);
            $table->decimal('costo_seguro', 12, 2)->default(0);
            $table->decimal('costo_total', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exim_muestras');
    }
};
