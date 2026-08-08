<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_profile_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('section');
            $table->string('title');
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->string('link')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('section');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_profile_items');
    }
};
