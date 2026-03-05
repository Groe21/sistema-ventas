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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Vendedor
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('cash_register_id')->nullable()->constrained()->onDelete('set null');
            
            // Número de factura/comprobante
            $table->string('invoice_number', 50)->unique();
            $table->date('sale_date');
            
            // Montos
            $table->decimal('subtotal', 10, 2); // Sin impuestos
            $table->decimal('iva_amount', 10, 2)->default(0); // Monto IVA (12%)
            $table->decimal('ice_amount', 10, 2)->default(0); // Monto ICE si aplica
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2); // Total con impuestos
            
            // Método de pago
            $table->enum('payment_method', ['cash', 'card', 'transfer', 'credit'])->default('cash');
            $table->enum('payment_status', ['paid', 'pending', 'partial'])->default('paid');
            
            // Estado de la venta
            $table->enum('status', ['completed', 'cancelled', 'refunded'])->default('completed');
            
            // Notas
            $table->text('notes')->nullable();
            
            // Autorización SRI (para futuro)
            $table->string('sri_authorization')->nullable();
            $table->string('access_key')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index(['business_id', 'sale_date']);
            $table->index('invoice_number');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
