<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->text('productos_json')->nullable()->after('producto');
            $table->decimal('descuento_porcentaje', 5, 2)->default(0)->after('impuesto');
            $table->decimal('descuento_monto', 12, 2)->default(0)->after('descuento_porcentaje');
            $table->text('condiciones')->nullable()->after('total');
        });
    }

    public function down(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->dropColumn(['productos_json', 'descuento_porcentaje', 'descuento_monto', 'condiciones']);
        });
    }
};
