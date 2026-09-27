<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Market;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed role-based test users (admin, farmer, customer)
        $this->call(RoleTestUsersSeeder::class);

        // -------------------------------------------------------
        // Seed Markets
        // -------------------------------------------------------
        $markets = [
            [
                'name' => 'Karachi Central Farmers Market',
                'address' => 'MA Jinnah Road, Karachi, Sindh',
                'latitude' => 24.8607,
                'longitude' => 67.0011,
                'description' => 'A bustling weekend market featuring fresh produce from local farmers.',
            ],
            [
                'name' => 'Lahore Green Market',
                'address' => 'Liberty Market, Gulberg III, Lahore, Punjab',
                'latitude' => 31.5204,
                'longitude' => 74.3587,
                'description' => 'Organic and fresh produce every weekend in the heart of Lahore.',
            ],
            [
                'name' => 'Islamabad Eco Bazaar',
                'address' => 'Jinnah Super Market, F-7 Markaz, Islamabad',
                'latitude' => 33.7215,
                'longitude' => 73.0433,
                'description' => 'Eco-friendly market promoting sustainable farming practices.',
            ],
        ];

        $createdMarkets = [];
        foreach ($markets as $market) {
            $createdMarkets[] = Market::firstOrCreate(
                ['name' => $market['name']],
                $market
            );
        }

        // -------------------------------------------------------
        // Seed Categories
        // -------------------------------------------------------
        $categories = [
            ['name' => 'Vegetables', 'description' => 'Fresh farm vegetables sourced locally.'],
            ['name' => 'Fruits',     'description' => 'Seasonal fresh fruits from local orchards.'],
            ['name' => 'Dairy',      'description' => 'Farm-fresh dairy products including milk, cheese, and yogurt.'],
        ];

        $createdCategories = [];
        foreach ($categories as $category) {
            $createdCategories[$category['name']] = Category::firstOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }

        // -------------------------------------------------------
        // Associate farmer with a market & seed Products
        // -------------------------------------------------------
        $farmerUser = User::where('email', 'farmer@marketlink.test')->first();

        if ($farmerUser && $farmerUser->farmerProfile) {
            $farmerProfile = $farmerUser->farmerProfile;

            // Associate farmer with the first market (if not already linked)
            $firstMarket = $createdMarkets[0];
            if (! $farmerProfile->markets()->where('market_id', $firstMarket->id)->exists()) {
                $farmerProfile->markets()->attach($firstMarket->id);
            }

            // Sample products
            $products = [
                [
                    'farmer_profile_id' => $farmerProfile->id,
                    'category_id' => $createdCategories['Vegetables']->id,
                    'name' => 'Fresh Spinach',
                    'description' => 'Locally grown baby spinach, harvested fresh.',
                    'price' => 120.00,
                    'unit' => 'kg',
                    'stock_quantity' => 50,
                    'is_available' => true,
                ],
                [
                    'farmer_profile_id' => $farmerProfile->id,
                    'category_id' => $createdCategories['Fruits']->id,
                    'name' => 'Alphonso Mangoes',
                    'description' => 'Sweet and juicy Alphonso mangoes from Sindh.',
                    'price' => 350.00,
                    'unit' => 'dozen',
                    'stock_quantity' => 30,
                    'is_available' => true,
                ],
                [
                    'farmer_profile_id' => $farmerProfile->id,
                    'category_id' => $createdCategories['Dairy']->id,
                    'name' => 'Farm Fresh Yogurt',
                    'description' => 'Creamy full-fat yogurt made from pure buffalo milk.',
                    'price' => 180.00,
                    'unit' => 'kg',
                    'stock_quantity' => 25,
                    'is_available' => true,
                ],
                [
                    'farmer_profile_id' => $farmerProfile->id,
                    'category_id' => $createdCategories['Vegetables']->id,
                    'name' => 'Organic Tomatoes',
                    'description' => 'Vine-ripened organic tomatoes, pesticide-free.',
                    'price' => 95.00,
                    'unit' => 'kg',
                    'stock_quantity' => 80,
                    'is_available' => true,
                ],
            ];

            foreach ($products as $product) {
                Product::firstOrCreate(
                    [
                        'farmer_profile_id' => $product['farmer_profile_id'],
                        'name' => $product['name'],
                    ],
                    $product
                );
            }
        }
    }
}
