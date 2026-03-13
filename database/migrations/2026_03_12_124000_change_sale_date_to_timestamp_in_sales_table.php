<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convertir fecha de venta a timestamp para almacenar hora exacta.
        DB::statement("ALTER TABLE sales ALTER COLUMN sale_date TYPE timestamp(0) without time zone USING sale_date::timestamp");

        // Backfill: usar created_at para ventas historicas y recuperar hora real.
        DB::statement("UPDATE sales SET sale_date = created_at WHERE created_at IS NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE sales ALTER COLUMN sale_date TYPE date USING sale_date::date");
    }
};
