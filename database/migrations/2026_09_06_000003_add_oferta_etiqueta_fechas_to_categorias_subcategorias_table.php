<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('categorias', 'etiquetaOferta')) {
            Schema::table('categorias', function (Blueprint $table) {
                $table->string('etiquetaOferta', 255)->nullable()->after('finOferta');
            });
        }
        if (!Schema::hasColumn('categorias', 'fechaInicioOferta')) {
            Schema::table('categorias', function (Blueprint $table) {
                $table->date('fechaInicioOferta')->nullable()->after('etiquetaOferta');
            });
        }

        if (!Schema::hasColumn('subcategorias', 'etiquetaOferta')) {
            Schema::table('subcategorias', function (Blueprint $table) {
                $table->string('etiquetaOferta', 255)->nullable()->after('finOferta');
            });
        }
        if (!Schema::hasColumn('subcategorias', 'fechaInicioOferta')) {
            Schema::table('subcategorias', function (Blueprint $table) {
                $table->date('fechaInicioOferta')->nullable()->after('etiquetaOferta');
            });
        }
    }

    public function down()
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropColumn(['etiquetaOferta', 'fechaInicioOferta']);
        });

        Schema::table('subcategorias', function (Blueprint $table) {
            $table->dropColumn(['etiquetaOferta', 'fechaInicioOferta']);
        });
    }
};