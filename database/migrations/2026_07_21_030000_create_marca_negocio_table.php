<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("marca_negocio", function (Blueprint $table) {
            $table->id();
            $table->foreignId("marca_id")->constrained("marcas")->onDelete("cascade");
            $table->foreignId("negocio_id")->constrained("negocios")->onDelete("cascade");
            $table->unique(["marca_id", "negocio_id"]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("marca_negocio");
    }
};
