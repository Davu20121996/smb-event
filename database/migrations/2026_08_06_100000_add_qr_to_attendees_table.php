<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('attendees')) {
            Schema::table('attendees', function (Blueprint $table) {
                if (!Schema::hasColumn('attendees', 'qr')) {
                    $table->string('qr', 50)->nullable()->unique()->after('interested_products');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('attendees') && Schema::hasColumn('attendees', 'qr')) {
            Schema::table('attendees', function (Blueprint $table) {
                $table->dropUnique(['qr']);
                $table->dropColumn('qr');
            });
        }
    }
};