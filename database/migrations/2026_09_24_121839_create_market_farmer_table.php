<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('market_farmer', function (Blueprint $table) {
            $table->id();

            $table->foreignId('market_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->foreignId('farmer_profile_id')
                  ->constrained('farmer_profiles')
                  ->onDelete('cascade');

            $table->timestamps();

            $table->unique(['market_id', 'farmer_profile_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('market_farmer');
    }
};