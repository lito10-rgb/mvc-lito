<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresa_logos', function (Blueprint $table) {
            $table->foreignId('negocio_id')->nullable()->constrained('negocios')->onDelete('set null')->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('empresa_logos', function (Blueprint $table) {
            $table->dropForeign(['negocio_id']);
            $table->dropColumn('negocio_id');
        });
    }
};
