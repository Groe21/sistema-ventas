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
        Schema::create('sale_payment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->string('denomination_type'); // 'bill' o 'coin'
            $table->decimal('denomination_value', 8, 2); // 50, 100, 20, 10, 5, 1, 0.50, 0.25, etc.
            $table->integer('quantity');
            $table->string('series')->nullable(); // Serie del billete (requerido para 50 y 100)
            $table->decimal('subtotal', 10, 2); // cantidad * valor
            $table->timestamps();

            // Index para búsqueda rápida
            $table->index(['sale_id', 'denomination_value']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_payment_details');
    }
};
