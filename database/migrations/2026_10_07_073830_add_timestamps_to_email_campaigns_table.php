<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('email_campaigns', 'created_at')) {
            Schema::table('email_campaigns', function (Blueprint $table) {
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('email_campaigns', 'created_at')) {
            Schema::table('email_campaigns', function (Blueprint $table) {
                $table->dropTimestamps();
            });
        }
    }
};