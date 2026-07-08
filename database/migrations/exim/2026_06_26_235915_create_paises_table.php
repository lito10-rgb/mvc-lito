<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('paises')) {
            Schema::create('paises', function (Blueprint $table) {
                $table->integer('id')->unsigned()->autoIncrement();
                $table->string('nombre', 150);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('paises');
    }
};
