<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->unsignedBigInteger('subcategoria_id')->nullable()->after('categoria_id');
            $table->text('descripcion')->nullable()->after('subcategoria_id');
        });
    }

    public function down(): void
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->dropColumn(['subcategoria_id', 'descripcion']);
        });
    }
};
