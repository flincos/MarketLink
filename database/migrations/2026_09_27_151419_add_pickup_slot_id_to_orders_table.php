<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'pickup_slot_id')) {
                $table->foreignId('pickup_slot_id')
                    ->nullable()
                    ->after('market_id');
            }
        });

        // Then add the foreign key separately
        Schema::table('orders', function (Blueprint $table) {
            // Guard in case FK already exists
            $table->foreign('pickup_slot_id')
                ->references('id')
                ->on('pickup_slots')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop FK if exists, then column
            $table->dropForeign(['pickup_slot_id']);
            $table->dropColumn('pickup_slot_id');
        });
    }
};