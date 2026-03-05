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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            
            // Información básica del producto
            $table->string('code', 50)->unique(); // Código de barras o SKU
            $table->string('name');
            $table->text('description')->nullable();
            
            // Categorización
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            
            // Precios e impuestos
            $table->decimal('cost_price', 10, 2)->default(0); // Precio de costo
            $table->decimal('sale_price', 10, 2); // Precio de venta
            $table->boolean('has_iva')->default(true); // Tiene IVA (12% en Ecuador)
            $table->boolean('has_ice')->default(false); // Tiene ICE (Impuesto Consumos Especiales)
            
            // Inventario
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(0); // Stock mínimo para alertas
            $table->enum('stock_type', ['product', 'service'])->default('product');
            
            // Imagen
            $table->string('image')->nullable();
            
            // Estado
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices para búsquedas rápidas
            $table->index(['business_id', 'is_active']);
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
