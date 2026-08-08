<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('attendees')) {
            Schema::table('attendees', function (Blueprint $table) {
                if (!Schema::hasColumn('attendees', 'voucher_id')) {
                    $table->unsignedBigInteger('voucher_id')->nullable()->after('status');
                }
                if (!Schema::hasColumn('attendees', 'voucher_code')) {
                    $table->string('voucher_code', 50)->nullable()->after('voucher_id');
                }
                if (!Schema::hasColumn('attendees', 'discount_amount')) {
                    $table->decimal('discount_amount', 10, 2)->default(0)->after('voucher_code');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('attendees')) {
            Schema::table('attendees', function (Blueprint $table) {
                foreach (['voucher_id', 'voucher_code', 'discount_amount'] as $column) {
                    if (Schema::hasColumn('attendees', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
