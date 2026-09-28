<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // This migration originally added pickup_slot_id + FK.
        // The column already exists in the database.
        // FK will be handled by a later migration to avoid conflicts.
    }

    public function down(): void
    {
        // Intentionally left blank. Column/FK handled elsewhere.
    }
};