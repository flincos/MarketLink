<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Make pickup_slot_id nullable (MySQL-specific statement)
        // We assume column is currently BIGINT UNSIGNED NOT NULL
        DB::statement('ALTER TABLE orders MODIFY pickup_slot_id BIGINT UNSIGNED NULL');

        // 2. Drop existing FK on pickup_slot_id if it exists
        try {
            DB::statement('ALTER TABLE orders DROP FOREIGN KEY orders_pickup_slot_id_foreign');
        } catch (\Throwable $e) {
            // ignore if FK not present
        }

        // 3. Add FK with ON DELETE SET NULL
        DB::statement(
            'ALTER TABLE orders ADD CONSTRAINT orders_pickup_slot_id_foreign
             FOREIGN KEY (pickup_slot_id) REFERENCES pickup_slots(id) ON DELETE SET NULL'
        );
    }

    public function down(): void
    {
        // Reverse: drop FK and make column NOT NULL again (if needed)
        try {
            DB::statement('ALTER TABLE orders DROP FOREIGN KEY orders_pickup_slot_id_foreign');
        } catch (\Throwable $e) {
            // ignore
        }

        DB::statement('ALTER TABLE orders MODIFY pickup_slot_id BIGINT UNSIGNED NOT NULL');
    }
};