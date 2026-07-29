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
        Schema::table('negocios', function (Blueprint $table) {
            $table->text('logo')->nullable()->after('dominio');
            $table->text('banner_entrada')->nullable()->after('logo');
            $table->text('banner_categoria')->nullable()->after('banner_entrada');
            $table->text('banner_subcategoria')->nullable()->after('banner_categoria');
            $table->string('color_primary', 20)->nullable()->after('banner_subcategoria');
            $table->string('color_secondary', 20)->nullable()->after('color_primary');
            $table->string('color_accent', 20)->nullable()->after('color_secondary');
            $table->string('color_accent_light', 20)->nullable()->after('color_accent');
            $table->string('footer_phone', 50)->nullable()->after('color_accent_light');
            $table->string('footer_email', 100)->nullable()->after('footer_phone');
            $table->string('footer_whatsapp', 50)->nullable()->after('footer_email');
            $table->string('footer_address', 255)->nullable()->after('footer_whatsapp');
            $table->string('footer_facebook', 255)->nullable()->after('footer_address');
            $table->string('footer_twitter', 255)->nullable()->after('footer_facebook');
            $table->string('footer_instagram', 255)->nullable()->after('footer_twitter');
            $table->string('footer_linkedin', 255)->nullable()->after('footer_instagram');
            $table->text('footer_html')->nullable()->after('footer_linkedin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('negocios', function (Blueprint $table) {
            $table->dropColumn([
                'logo', 'banner_entrada', 'banner_categoria', 'banner_subcategoria',
                'color_primary', 'color_secondary', 'color_accent', 'color_accent_light',
                'footer_phone', 'footer_email', 'footer_whatsapp', 'footer_address',
                'footer_facebook', 'footer_twitter', 'footer_instagram', 'footer_linkedin',
                'footer_html',
            ]);
        });
    }
};
