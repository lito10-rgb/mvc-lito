<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comercio', function (Blueprint $table) {
           $table->id();
            $table->float('impuesto');
            $table->float('envioNacional');
            $table->float('envioInternacional');
            $table->float('tasaMinimaNal');
            $table->float('tasaMinimaInt');
            $table->text('pais');
            $table->text('modoPaypal');
            $table->text('clienteIdPaypal');
            $table->text('llaveSecretaPaypal');
            $table->text('modoPayu');
            $table->integer('merchantIdPayu');
            $table->integer('accountIdPayu');
            $table->text('apiKeyPayu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comercio');
    }
};
