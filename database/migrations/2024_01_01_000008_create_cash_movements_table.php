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
        Schema::create('cash_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->foreignId('cash_register_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Usuario que registra
            $table->foreignId('sale_id')->nullable()->constrained()->onDelete('set null'); // Si es por venta
            
            // Tipo de movimiento
            $table->enum('type', ['income', 'expense', 'initial', 'closing'])->default('income');
            
            // Categorías de movimientos
            $table->enum('category', [
                'sale',              // Venta
                'expense',           // Gasto
                'withdrawal',        // Retiro
                'deposit',           // Depósito
                'adjustment',        // Ajuste
                'initial_balance',   // Saldo inicial
                'closing_balance',   // Saldo de cierre
                'other'              // Otro
            ])->default('sale');
            
            // Monto y descripción
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->string('reference')->nullable(); // Número de factura, recibo, etc.
            
            // Método de pago relacionado
            $table->enum('payment_method', ['cash', 'card', 'transfer', 'other'])->default('cash');
            
            $table->timestamps();
            
            // Índices
            $table->index(['cash_register_id', 'type']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_movements');
    }
};
