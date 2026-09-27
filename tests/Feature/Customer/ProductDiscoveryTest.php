<?php

use App\Models\Category;
use App\Models\FarmerProfile;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createDiscoveryProduct(
    string $name,
    string $status = 'approved',
    bool $available = true,
    int $stock = 5,
    ?Category $category = null
): Product {
    $farmerUser = User::factory()->create(['role' => 'farmer']);

    $farmer = FarmerProfile::create([
        'user_id' => $farmerUser->id,
        'stall_name' => 'Test Stall',
        'contact_person' => 'Test Farmer',
        'contact_number' => '123456789',
        'address' => 'Test Address',
        'status' => $status,
    ]);

    $category ??= Category::create([
        'name' => 'Category '.uniqid(),
    ]);

    return Product::create([
        'farmer_profile_id' => $farmer->id,
        'category_id' => $category->id,
        'name' => $name,
        'price' => 10.00,
        'stock_quantity' => $stock,
        'is_available' => $available,
    ]);
}

test('customer can search products by name', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $matching = createDiscoveryProduct('Fresh Tomatoes');
    createDiscoveryProduct('Sweet Mangoes');

    $response = $this->actingAs($customer)
        ->get(route('customer.products.index', ['search' => 'Tomatoes']));

    $response->assertOk()
        ->assertViewHas('products', function ($products) use ($matching) {
            return $products->contains('id', $matching->id)
                && ! $products->contains('name', 'Sweet Mangoes');
        });
});

test('customer can filter products by category', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $category1 = Category::create(['name' => 'Vegetables']);
    $category2 = Category::create(['name' => 'Fruits']);

    $matching = createDiscoveryProduct('Carrot', category: $category1);
    createDiscoveryProduct('Apple', category: $category2);

    $response = $this->actingAs($customer)
        ->get(route('customer.products.index', ['category' => $category1->id]));

    $response->assertOk()
        ->assertViewHas('products', function ($products) use ($matching) {
            return $products->contains('id', $matching->id)
                && $products->count() === 1;
        });
});

test('customer can filter products that are in stock', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $matching = createDiscoveryProduct('Available Product', stock: 5);
    createDiscoveryProduct('Empty Product', stock: 0);

    $response = $this->actingAs($customer)
        ->get(route('customer.products.index', ['availability' => 'in_stock']));

    $response->assertOk()
        ->assertViewHas('products', function ($products) use ($matching) {
            return $products->contains('id', $matching->id)
                && ! $products->contains('name', 'Empty Product');
        });
});

test('customer can filter products that are out of stock', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $matching = createDiscoveryProduct('Empty Product', stock: 0);
    createDiscoveryProduct('Available Product', stock: 5);

    $response = $this->actingAs($customer)
        ->get(route('customer.products.index', ['availability' => 'out_of_stock']));

    $response->assertOk()
        ->assertViewHas('products', function ($products) use ($matching) {
            return $products->contains('id', $matching->id)
                && ! $products->contains('name', 'Available Product');
        });
});

test('products from unapproved farmers are excluded', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $approved = createDiscoveryProduct('Approved Product', 'approved');
    createDiscoveryProduct('Pending Product', 'pending');

    $response = $this->actingAs($customer)
        ->get(route('customer.products.index'));

    $response->assertOk()
        ->assertViewHas('products', function ($products) use ($approved) {
            return $products->contains('id', $approved->id)
                && ! $products->contains('name', 'Pending Product');
        });
});

test('customer can view an approved farmers product', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $product = createDiscoveryProduct('Approved Product');

    $this->actingAs($customer)
        ->get(route('customer.products.show', $product))
        ->assertOk();
});

test('customer cannot view a product from an unapproved farmer', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $product = createDiscoveryProduct('Pending Product', 'pending');

    $this->actingAs($customer)
        ->get(route('customer.products.show', $product))
        ->assertNotFound();
});
