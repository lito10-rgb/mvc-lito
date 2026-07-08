<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('user_scores')) {
            Schema::create('user_scores', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->integer('puntuacion')->default(0);
                $table->integer('puntuacion_usuario')->default(0);
                $table->integer('puntuacion_precio')->default(0);
                $table->string('condicion')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_scores');
    }
};
