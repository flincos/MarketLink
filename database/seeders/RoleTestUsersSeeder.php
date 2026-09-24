<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleTestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@marketlink.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $farmer = User::create([
            'name' => 'Farmer User',
            'email' => 'farmer@marketlink.test',
            'password' => Hash::make('password'),
            'role' => 'farmer',
        ]);

        $farmer->farmerProfile()->create([
            'stall_name' => 'Green Valley Produce',
            'contact_person' => 'Farmer User',
            'contact_number' => '0771234567',
            'address' => '123 Karachi Street',
            'status' => 'approved',
        ]);

        User::create([
            'name' => 'Customer User',
            'email' => 'customer@marketlink.test',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
    }
}
