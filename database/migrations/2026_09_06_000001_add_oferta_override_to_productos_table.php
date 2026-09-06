<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->integer('ofertaCategoria')->nullable()->after('ofertadoPorSubCategoria');
            $table->integer('ofertaSubcategoria')->nullable()->after('ofertaCategoria');
        });
    }

    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['ofertaCategoria', 'ofertaSubcategoria']);
        });
    }
};