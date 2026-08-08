<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('event_id')->nullable()->index();
            $table->string('code', 50)->unique();
            $table->string('name', 255);
            $table->enum('type', ['discount_percent', 'discount_fixed', 'free_ticket', 'gift', 'priority_seat']);
            $table->decimal('value', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->integer('max_uses')->nullable();
            $table->integer('used_count')->default(0);
            $table->boolean('is_single_use')->default(false);
            $table->boolean('is_assignable')->default(true);
            $table->dateTime('valid_from')->nullable();
            $table->dateTime('valid_until')->nullable();
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
