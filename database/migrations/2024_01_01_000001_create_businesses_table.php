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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ruc', 13)->unique(); // RUC ecuatoriano (13 dígitos)
            $table->string('commercial_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            
            // Configuración fiscal Ecuador
            $table->string('accountant_name')->nullable();
            $table->string('legal_representative')->nullable();
            $table->boolean('special_taxpayer')->default(false);
            $table->boolean('required_accounting')->default(false);
            
            // Logo y branding
            $table->string('logo')->nullable();
            
            // Estado y suscripción
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->date('subscription_start')->nullable();
            $table->date('subscription_end')->nullable();
            $table->enum('plan', ['trial', 'basic', 'pro', 'enterprise'])->default('trial');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
