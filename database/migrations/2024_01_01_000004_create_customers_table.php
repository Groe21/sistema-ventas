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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            
            // Información personal/empresa
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            
            // Identificación fiscal Ecuador
            $table->enum('identification_type', ['cedula', 'ruc', 'pasaporte', 'consumidor_final'])
                  ->default('consumidor_final');
            $table->string('identification', 13)->nullable(); // Cédula: 10 dígitos, RUC: 13 dígitos
            
            // Dirección
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            
            // Información comercial
            $table->decimal('credit_limit', 10, 2)->default(0);
            $table->integer('credit_days')->default(0);
            $table->text('notes')->nullable();
            
            // Estado
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index(['business_id', 'is_active']);
            $table->index('identification');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
