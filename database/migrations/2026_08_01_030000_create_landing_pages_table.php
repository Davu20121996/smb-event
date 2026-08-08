<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_pages', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title');

            $table->string('slug')->unique();

            $table->longText('content')->nullable();

            $table->string('form_title')->nullable();

            $table->string('button_title')->nullable();

            $table->string('crm_tag')->nullable();

            $table->boolean('pdf_enabled')->default(0);

            $table->string('pdf_source')->nullable();

            $table->string('pdf_url')->nullable();

            $table->string('download_title')->nullable();

            $table->string('download_button_title')->nullable();

            $table->string('report_url')->nullable();

            $table->boolean('is_published')->default(1);

            $table->timestamps();

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_pages');
    }
};
