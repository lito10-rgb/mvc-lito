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
        Schema::create('post_post_platform', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('post_id');
    $table->unsignedBigInteger('post_platform_id');
    $table->timestamps();

    $table->foreign('post_id')->references('id')->on('posts')->cascadeOnDelete();
    $table->foreign('post_platform_id')->references('id')->on('post_platforms')->cascadeOnDelete();

    $table->unique(['post_id', 'post_platform_id']);
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_platforms');
    }
};
