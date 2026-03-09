<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('sale_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('points_earned')->default(0);
            $table->integer('points_used')->default(0);
            $table->string('description')->nullable();
            $table->timestamps();

            $table->index(['business_id', 'customer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
    }
};
