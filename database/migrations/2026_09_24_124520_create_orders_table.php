<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->foreignId('farmer_profile_id')
                  ->constrained('farmer_profiles')
                  ->onDelete('cascade');

            $table->foreignId('market_id')
                  ->constrained()
                  ->onDelete('restrict');

            $table->date('pickup_date');
            $table->time('pickup_time');

            $table->decimal('total_amount', 10, 2)->default(0);

            $table->enum('status', [
                'pending',
                'confirmed',
                'ready',
                'completed',
                'cancelled'
            ])->default('pending');

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};