<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->unsignedInteger('distrito_id')->nullable()->after('provincia_id');
            $table->string('codigo_postal', 20)->nullable()->after('descripcion');
        });
    }

    public function down(): void
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->dropColumn(['distrito_id', 'codigo_postal']);
        });
    }
};
