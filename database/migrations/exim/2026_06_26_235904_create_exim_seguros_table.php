<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exim_seguros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->decimal('porcentaje', 5, 2)->default(0);
            $table->decimal('costo_base', 12, 2)->default(0);
            $table->foreignId('moneda_id')->nullable()->constrained('exim_monedas')->nullOnDelete();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exim_seguros');
    }
};
