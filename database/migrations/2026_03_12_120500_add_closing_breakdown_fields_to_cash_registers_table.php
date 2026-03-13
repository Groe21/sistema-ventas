<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cash_registers', function (Blueprint $table) {
            $table->json('cash_breakdown')->nullable()->after('difference');
            $table->decimal('counted_card_amount', 10, 2)->nullable()->after('actual_amount');
            $table->decimal('counted_transfer_amount', 10, 2)->nullable()->after('counted_card_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_registers', function (Blueprint $table) {
            $table->dropColumn(['cash_breakdown', 'counted_card_amount', 'counted_transfer_amount']);
        });
    }
};
