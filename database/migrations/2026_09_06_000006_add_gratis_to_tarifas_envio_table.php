<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tarifas_envio', function (Blueprint $table) {
            $table->boolean('gratis')->default(false)->after('costo');
        });
    }

    public function down(): void
    {
        Schema::table('tarifas_envio', function (Blueprint $table) {
            $table->dropColumn('gratis');
        });
    }
};