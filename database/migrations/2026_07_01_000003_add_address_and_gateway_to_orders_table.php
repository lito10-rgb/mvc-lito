<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('gateway', 50)->nullable()->after('status');
            $table->string('direccion', 255)->nullable()->after('gateway');
            $table->string('ciudad', 100)->nullable()->after('direccion');
            $table->string('departamento', 100)->nullable()->after('ciudad');
            $table->string('telefono', 20)->nullable()->after('departamento');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['gateway', 'direccion', 'ciudad', 'departamento', 'telefono']);
        });
    }
};
