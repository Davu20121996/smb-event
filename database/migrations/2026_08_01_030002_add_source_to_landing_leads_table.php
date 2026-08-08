<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('landing_leads', function (Blueprint $table) {
            $table->string('document_url')->nullable();
            $table->string('source_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('landing_leads', function (Blueprint $table) {
            $table->dropColumn(['document_url', 'source_url']);
        });
    }
};
