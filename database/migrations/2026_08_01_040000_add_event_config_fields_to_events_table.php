<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('countdown_enabled')->default(0);
            $table->timestamp('registration_deadline')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('favicon_url')->nullable();
            $table->string('og_image')->nullable();
            $table->boolean('calendar_enabled')->default(1);
            $table->string('zalo_url')->nullable();
            $table->string('fanpage_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'countdown_enabled',
                'registration_deadline',
                'meta_title',
                'meta_description',
                'favicon_url',
                'og_image',
                'calendar_enabled',
                'zalo_url',
                'fanpage_url',
            ]);
        });
    }
};
