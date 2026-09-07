<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('cupon_id')->nullable()->after('telefono');
            $table->string('cupon_codigo', 50)->nullable()->after('cupon_id');
            $table->decimal('cupon_descuento', 10, 2)->nullable()->after('cupon_codigo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['cupon_id', 'cupon_codigo', 'cupon_descuento']);
        });
    }
};
