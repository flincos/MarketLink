<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleTestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@marketlink.test'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $farmer = User::firstOrCreate(
            ['email' => 'farmer@marketlink.test'],
            [
                'name' => 'Farmer User',
                'password' => Hash::make('password'),
                'role' => 'farmer',
            ]
        );

        // Create farmerProfile only if it doesn't already exist
        if (! $farmer->farmerProfile) {
            $farmer->farmerProfile()->create([
                'stall_name' => 'Green Valley Produce',
                'contact_person' => 'Farmer User',
                'contact_number' => '0771234567',
                'address' => '123 Karachi Street',
                'status' => 'approved',
            ]);
        }

        User::firstOrCreate(
            ['email' => 'customer@marketlink.test'],
            [
                'name' => 'Customer User',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );
    }
}
