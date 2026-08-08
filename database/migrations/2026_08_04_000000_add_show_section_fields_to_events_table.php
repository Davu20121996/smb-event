<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('show_gallery')->default(1)->after('fanpage_url');
            $table->boolean('show_sponsors')->default(1)->after('show_gallery');
            $table->boolean('show_tickets')->default(1)->after('show_sponsors');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['show_gallery', 'show_sponsors', 'show_tickets']);
        });
    }
};
