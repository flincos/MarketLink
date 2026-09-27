<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->foreignId('farmer_profile_id')
                ->nullable()
                ->after('product_id')
                ->constrained('farmer_profiles')
                ->cascadeOnDelete();

            $table->unique(
                ['user_id', 'farmer_profile_id'],
                'favorites_user_farmer_unique'
            );
        });

        Schema::table('favorites', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->dropUnique('favorites_user_farmer_unique');
            $table->dropForeign(['farmer_profile_id']);
            $table->dropColumn('farmer_profile_id');
        });

        Schema::table('favorites', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')
                ->nullable(false)
                ->change();
        });
    }
};
