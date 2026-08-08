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
                if (!Schema::hasColumn('attendees', 'checked_in_at')) {
                    $table->timestamp('checked_in_at')->nullable()->after('status');
                }
                if (!Schema::hasColumn('attendees', 'checked_in_by')) {
                    $table->unsignedBigInteger('checked_in_by')->nullable()->after('checked_in_at');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('attendees')) {
            Schema::table('attendees', function (Blueprint $table) {
                foreach (['checked_in_at', 'checked_in_by'] as $column) {
                    if (Schema::hasColumn('attendees', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};