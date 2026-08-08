<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('attendees')) {
            Schema::create('attendees', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('event_id')->nullable()->index();
                $table->string('name');
                $table->string('email');
                $table->string('phone')->nullable();
                $table->string('company')->nullable();
                $table->string('tax_code')->nullable();
                $table->string('company_size')->nullable();
                $table->string('interested_products')->nullable();
                $table->string('ticket_type')->nullable();
                $table->string('status')->default('pending');
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });

            return;
        }

        Schema::table('attendees', function (Blueprint $table) {
            $last = 'phone';

            foreach (['company', 'tax_code', 'company_size', 'interested_products'] as $column) {
                if (!Schema::hasColumn('attendees', $column)) {
                    $table->string($column)->nullable()->after($last);
                }
                $last = $column;
            }

            if (!Schema::hasColumn('attendees', 'ticket_type')) {
                $table->string('ticket_type')->nullable();
            }

            if (!Schema::hasColumn('attendees', 'notes')) {
                $table->text('notes')->nullable();
            }

            if (!Schema::hasColumn('attendees', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('attendees', function (Blueprint $table) {
            foreach (['tax_code', 'company_size', 'interested_products', 'notes', 'deleted_at'] as $column) {
                if (Schema::hasColumn('attendees', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};