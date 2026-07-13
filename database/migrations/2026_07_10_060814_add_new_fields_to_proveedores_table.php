<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->string('empresa', 255)->nullable()->after('ruc');
            $table->string('web', 255)->nullable()->after('empresa');
            $table->string('celular', 50)->nullable()->after('web');
            $table->string('email2', 255)->nullable()->after('email');
            $table->unsignedInteger('pais_id')->nullable()->after('direccion');
            $table->unsignedInteger('departamento_id')->nullable()->after('pais_id');
            $table->unsignedInteger('provincia_id')->nullable()->after('departamento_id');
            $table->string('facebook', 255)->nullable()->after('provincia_id');
            $table->string('instagram', 255)->nullable()->after('facebook');
            $table->unsignedBigInteger('categoria_id')->nullable()->after('instagram');
        });
    }

    public function down(): void
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->dropColumn([
                'empresa', 'web', 'celular', 'email2',
                'pais_id', 'departamento_id', 'provincia_id',
                'facebook', 'instagram', 'categoria_id',
            ]);
        });
    }
};
