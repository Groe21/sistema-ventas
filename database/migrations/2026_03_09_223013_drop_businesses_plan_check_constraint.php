<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the CHECK constraint left by the original enum definition
        DB::statement("ALTER TABLE businesses DROP CONSTRAINT IF EXISTS businesses_plan_check");
        // Also drop status constraint in case it causes issues
        DB::statement("ALTER TABLE businesses DROP CONSTRAINT IF EXISTS businesses_status_check");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add constraints if needed
    }
};
