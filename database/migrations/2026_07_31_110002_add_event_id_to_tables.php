<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'speakers',
            'schedules',
            'venues',
            'hotels',
            'galleries',
            'sponsors',
            'faqs',
            'amenities',
            'prices',
            'settings',
        ];

        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'event_id')) {
                continue;
            }

            Schema::table($table, function (Blueprint $table) {
                $table->unsignedInteger('event_id')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'speakers',
            'schedules',
            'venues',
            'hotels',
            'galleries',
            'sponsors',
            'faqs',
            'amenities',
            'prices',
            'settings',
        ];

        foreach ($tables as $table) {
            if (!Schema::hasColumn($table, 'event_id')) {
                continue;
            }

            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('event_id');
            });
        }
    }
};
