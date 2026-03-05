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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Detalles del producto al momento de la venta
            $table->string('product_name'); // Guardamos el nombre por si el producto se elimina
            $table->string('product_code', 50);
            
            // Cantidades y precios
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2); // Precio unitario sin impuestos
            $table->decimal('subtotal', 10, 2); // quantity * unit_price
            
            // Impuestos
            $table->boolean('has_iva')->default(true);
            $table->decimal('iva_amount', 10, 2)->default(0);
            $table->boolean('has_ice')->default(false);
            $table->decimal('ice_amount', 10, 2)->default(0);
            
            // Descuentos
            $table->decimal('discount', 10, 2)->default(0);
            
            // Total de la línea
            $table->decimal('total', 10, 2);
            
            $table->timestamps();
            
            // Índices
            $table->index('sale_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
