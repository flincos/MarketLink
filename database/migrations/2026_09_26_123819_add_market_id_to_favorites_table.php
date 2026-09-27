<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->foreignId('market_id')
                ->nullable()
                ->after('farmer_profile_id')
                ->constrained('markets')
                ->cascadeOnDelete();

            $table->unique(
                ['user_id', 'market_id'],
                'favorites_user_market_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->dropUnique('favorites_user_market_unique');
            $table->dropConstrainedForeignId('market_id');
        });
    }
};
