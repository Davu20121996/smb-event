<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title');

            $table->string('slug')->unique()->nullable();

            $table->longText('excerpt')->nullable();

            $table->longText('content')->nullable();

            $table->boolean('is_published')->default(1);

            $table->timestamps();

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
