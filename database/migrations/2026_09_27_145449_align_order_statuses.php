<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            // Temporarily allow both old and new values.
            DB::statement("
                ALTER TABLE orders
                MODIFY status ENUM(
                    'pending',
                    'confirmed',
                    'placed',
                    'accepted',
                    'declined',
                    'ready',
                    'completed',
                    'cancelled'
                ) NOT NULL DEFAULT 'placed'
            ");

            DB::table('orders')
                ->where('status', 'pending')
                ->update(['status' => 'placed']);

            DB::table('orders')
                ->where('status', 'confirmed')
                ->update(['status' => 'accepted']);

            // Remove the obsolete status values.
            DB::statement("
                ALTER TABLE orders
                MODIFY status ENUM(
                    'placed',
                    'accepted',
                    'declined',
                    'ready',
                    'completed',
                    'cancelled'
                ) NOT NULL DEFAULT 'placed'
            ");

            return;
        }

        // SQLite is used by the feature tests.
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status')->default('placed')->change();
        });

        DB::table('orders')
            ->where('status', 'pending')
            ->update(['status' => 'placed']);

        DB::table('orders')
            ->where('status', 'confirmed')
            ->update(['status' => 'accepted']);
    }

   public function down(): void
{
    $driver = Schema::getConnection()->getDriverName();

    if (DB::table('orders')->where('status', 'declined')->exists()) {
        throw new RuntimeException(
            'Cannot roll back order statuses while declined orders exist.'
        );
    }

    DB::table('orders')
        ->where('status', 'placed')
        ->update(['status' => 'pending']);

    DB::table('orders')
        ->where('status', 'accepted')
        ->update(['status' => 'confirmed']);

    if (in_array($driver, ['mysql', 'mariadb'], true)) {
        DB::statement("
            ALTER TABLE orders
            MODIFY status ENUM(
                'pending',
                'confirmed',
                'ready',
                'completed',
                'cancelled'
            ) NOT NULL DEFAULT 'pending'
        ");

        return;
    }

    Schema::table('orders', function (Blueprint $table) {
        $table->string('status')->default('pending')->change();
    });
}
};