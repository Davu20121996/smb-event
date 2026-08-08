<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('attendees') && Schema::hasColumn('attendees', 'code')) {
            Schema::table('attendees', function (Blueprint $table) {
                $table->string('code', 20)->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('attendees') && Schema::hasColumn('attendees', 'code')) {
            Schema::table('attendees', function (Blueprint $table) {
                $table->string('code', 20)->nullable(false)->change();
            });
        }
    }
};