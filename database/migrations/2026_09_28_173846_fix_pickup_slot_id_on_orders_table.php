<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Only run these alterations if the pickup_slot_id column exists.
        if (! Schema::hasColumn('orders', 'pickup_slot_id')) {
            return;
        }

        // 1. Make pickup_slot_id nullable (MySQL-specific statement)
        try {
            DB::statement('ALTER TABLE orders MODIFY pickup_slot_id BIGINT UNSIGNED NULL');
        } catch (\Throwable $e) {
            // Ignore if the column is already nullable or DB driver differs
        }

        // 2. Drop existing FK on pickup_slot_id if it exists
        try {
            DB::statement('ALTER TABLE orders DROP FOREIGN KEY orders_pickup_slot_id_foreign');
        } catch (\Throwable $e) {
            // ignore if FK not present
        }

        // 3. Add FK with ON DELETE SET NULL
        try {
            DB::statement(
                'ALTER TABLE orders ADD CONSTRAINT orders_pickup_slot_id_foreign
                 FOREIGN KEY (pickup_slot_id) REFERENCES pickup_slots(id) ON DELETE SET NULL'
            );
        } catch (\Throwable $e) {
            // ignore if constraint already present
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('orders', 'pickup_slot_id')) {
            return;
        }

        // Reverse: drop FK and make column NOT NULL again (if needed)
        try {
            DB::statement('ALTER TABLE orders DROP FOREIGN KEY orders_pickup_slot_id_foreign');
        } catch (\Throwable $e) {
            // ignore
        }

        try {
            DB::statement('ALTER TABLE orders MODIFY pickup_slot_id BIGINT UNSIGNED NOT NULL');
        } catch (\Throwable $e) {
            // ignore if already NOT NULL or differing schema
        }
    }
};