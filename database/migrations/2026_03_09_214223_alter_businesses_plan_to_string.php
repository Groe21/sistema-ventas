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
        // PostgreSQL: drop enum constraint and change to varchar
        DB::statement("ALTER TABLE businesses ALTER COLUMN plan TYPE VARCHAR(50)");
        DB::statement("ALTER TABLE businesses ALTER COLUMN plan SET DEFAULT 'starter'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE businesses ALTER COLUMN plan TYPE VARCHAR(50)");
    }
};
