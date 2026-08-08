<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');

            $table->string('label');

            $table->string('url')->nullable();

            $table->unsignedInteger('parent_id')->nullable();

            $table->integer('sort_order')->default(0);

            $table->boolean('is_active')->default(1);

            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
