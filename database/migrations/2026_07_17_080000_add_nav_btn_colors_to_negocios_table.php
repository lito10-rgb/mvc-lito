<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('negocios', function (Blueprint $table) {
            $table->string('color_nav_btn')->nullable()->after('color_accent_light');
            $table->string('color_nav_btn_texto')->nullable()->after('color_nav_btn');
        });
    }

    public function down(): void
    {
        Schema::table('negocios', function (Blueprint $table) {
            $table->dropColumn(['color_nav_btn', 'color_nav_btn_texto']);
        });
    }
};
