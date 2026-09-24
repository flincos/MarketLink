<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pickup_slots', function (Blueprint $table) {
            $table->id();

            $table->foreignId('farmer_profile_id')
                  ->constrained('farmer_profiles')
                  ->onDelete('cascade');

            $table->foreignId('market_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->date('date');

            $table->time('start_time');
            $table->time('end_time');

            $table->unsignedInteger('capacity')->default(10);

            $table->boolean('is_available')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pickup_slots');
    }
};