<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->integer('points_balance')->default(0);
            $table->timestamps();

            $table->unique(['business_id', 'customer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_points');
    }
};
