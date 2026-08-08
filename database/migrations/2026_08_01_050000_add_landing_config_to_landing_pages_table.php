<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->boolean('countdown_enabled')->default(0)->after('button_title');

            $table->timestamp('registration_deadline')->nullable()->after('countdown_enabled');

            $table->json('key_benefits')->nullable()->after('registration_deadline');

            $table->json('agenda')->nullable()->after('key_benefits');

            $table->string('speaker_name')->nullable()->after('agenda');

            $table->string('speaker_role')->nullable()->after('speaker_name');

            $table->string('speaker_company')->nullable()->after('speaker_role');

            $table->longText('speaker_bio')->nullable()->after('speaker_company');

            $table->boolean('calendar_enabled')->default(0)->after('report_url');

            $table->string('zalo_url')->nullable()->after('calendar_enabled');

            $table->string('fanpage_url')->nullable()->after('zalo_url');
        });
    }

    public function down(): void
    {
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->dropColumn([
                'countdown_enabled',
                'registration_deadline',
                'key_benefits',
                'agenda',
                'speaker_name',
                'speaker_role',
                'speaker_company',
                'speaker_bio',
                'calendar_enabled',
                'zalo_url',
                'fanpage_url',
            ]);
        });
    }
};
