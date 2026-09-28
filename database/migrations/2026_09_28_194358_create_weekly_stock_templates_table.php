<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_stock_templates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('farmer_profile_id')
                ->constrained('farmer_profiles')
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            // Default weekly quantity the farmer wants to have for this product
            $table->unsignedInteger('weekly_quantity')->default(0);

            // Optional: notes or description
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(['farmer_profile_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_stock_templates');
    }
};