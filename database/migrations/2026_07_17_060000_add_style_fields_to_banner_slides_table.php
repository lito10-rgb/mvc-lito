<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banner_slides', function (Blueprint $table) {
            $table->string('color_texto')->nullable()->after('subtitulo');
            $table->string('color_boton_fondo')->nullable()->after('boton_url');
            $table->string('color_boton_texto')->nullable()->after('color_boton_fondo');
            $table->string('posicion', 20)->default('center')->after('color_boton_texto');
        });
    }

    public function down(): void
    {
        Schema::table('banner_slides', function (Blueprint $table) {
            $table->dropColumn(['color_texto', 'color_boton_fondo', 'color_boton_texto', 'posicion']);
        });
    }
};
