<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_leads', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('landing_page_id');

            $table->string('name');

            $table->string('email');

            $table->string('phone')->nullable();

            $table->string('crm_tag')->nullable();

            $table->boolean('is_synced')->default(0);

            $table->timestamps();

            $table->foreign('landing_page_id')->references('id')->on('landing_pages')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_leads');
    }
};
