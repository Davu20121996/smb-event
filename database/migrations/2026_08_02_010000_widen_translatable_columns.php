<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WidenTranslatableColumns extends Migration
{
    public function up()
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->text('question')->change();
            $table->text('answer')->change();
        });

        Schema::table('prices', function (Blueprint $table) {
            $table->text('name')->change();
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->text('title')->change();
            $table->text('subtitle')->nullable()->change();
        });

        Schema::table('hotels', function (Blueprint $table) {
            $table->text('name')->change();
            $table->text('address')->nullable()->change();
        });

        Schema::table('venues', function (Blueprint $table) {
            $table->text('name')->change();
            $table->text('address')->change();
        });

        Schema::table('speakers', function (Blueprint $table) {
            $table->text('name')->change();
            $table->text('role')->nullable()->change();
            $table->text('company')->nullable()->change();
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->text('label')->change();
        });

        Schema::table('landing_pages', function (Blueprint $table) {
            $table->text('title')->change();
            $table->text('form_title')->nullable()->change();
            $table->text('button_title')->nullable()->change();
            $table->text('download_title')->nullable()->change();
            $table->text('download_button_title')->nullable()->change();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->text('title')->change();
            $table->text('tag')->nullable()->change();
        });

        Schema::table('key_benefits', function (Blueprint $table) {
            $table->text('title')->change();
        });
    }

    public function down()
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->string('question')->change();
            $table->string('answer')->change();
        });

        Schema::table('prices', function (Blueprint $table) {
            $table->string('name')->change();
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->string('title')->change();
            $table->string('subtitle')->nullable()->change();
        });

        Schema::table('hotels', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('address')->nullable()->change();
        });

        Schema::table('venues', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('address')->change();
        });

        Schema::table('speakers', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('role')->nullable()->change();
            $table->string('company')->nullable()->change();
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->string('label')->change();
        });

        Schema::table('landing_pages', function (Blueprint $table) {
            $table->string('title')->change();
            $table->string('form_title')->nullable()->change();
            $table->string('button_title')->nullable()->change();
            $table->string('download_title')->nullable()->change();
            $table->string('download_button_title')->nullable()->change();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->string('title')->change();
            $table->string('tag')->nullable()->change();
        });

        Schema::table('key_benefits', function (Blueprint $table) {
            $table->string('title')->change();
        });
    }
}
