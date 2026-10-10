<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('productos', 'etiquetaOferta')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->string('etiquetaOferta', 255)->nullable()->after('finOferta');
            });
        }
    }

    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('etiquetaOferta');
        });
    }
};