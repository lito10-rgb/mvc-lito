<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->foreignId('emisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('emisor_data')->nullable()->after('emisor_id');
            $table->foreignId('logo_id')->nullable()->constrained('empresa_logos')->nullOnDelete();
            $table->foreignId('condicion_id')->nullable()->constrained('condiciones_comerciales')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->dropForeign(['condicion_id']);
            $table->dropForeign(['logo_id']);
            $table->dropForeign(['emisor_id']);
            $table->dropColumn(['emisor_id', 'emisor_data', 'logo_id', 'condicion_id']);
        });
    }
};
