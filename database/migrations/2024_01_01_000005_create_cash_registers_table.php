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
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Usuario que abre la caja
            
            // Información de la caja
            $table->string('name')->default('Caja Principal');
            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();
            
            // Montos
            $table->decimal('opening_amount', 10, 2)->default(0); // Efectivo inicial
            $table->decimal('expected_amount', 10, 2)->default(0); // Efectivo esperado
            $table->decimal('actual_amount', 10, 2)->nullable(); // Efectivo real al cierre
            $table->decimal('difference', 10, 2)->nullable(); // Diferencia (faltante o sobrante)
            
            // Estado
            $table->enum('status', ['open', 'closed'])->default('open');
            
            // Notas
            $table->text('opening_notes')->nullable();
            $table->text('closing_notes')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index(['business_id', 'status']);
            $table->index('opened_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_registers');
    }
};
