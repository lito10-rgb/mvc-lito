<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tarifas_envio', function (Blueprint $table) {
            $table->unsignedBigInteger('subcategoria_id')->nullable()->after('categoria_id');
            $table->foreign('subcategoria_id')->references('id')->on('subcategorias')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tarifas_envio', function (Blueprint $table) {
            $table->dropForeign(['subcategoria_id']);
            $table->dropColumn('subcategoria_id');
        });
    }
};