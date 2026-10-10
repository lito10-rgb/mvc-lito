<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('productos', 'ofertaCategoria')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->integer('ofertaCategoria')->nullable()->after('ofertadoPorSubCategoria');
            });
        }
        if (!Schema::hasColumn('productos', 'ofertaSubcategoria')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->integer('ofertaSubcategoria')->nullable()->after('ofertaCategoria');
            });
        }
    }

    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['ofertaCategoria', 'ofertaSubcategoria']);
        });
    }
};