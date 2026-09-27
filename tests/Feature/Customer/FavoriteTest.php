<?php

use App\Models\Category;
use App\Models\FarmerProfile;
use App\Models\Favorite;
use App\Models\Market;
use App\Models\Product;
use App\Models\User;
use App\Notifications\ProductRestockedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

function createTestProduct(): Product
{
    $farmerUser = User::factory()->create([
        'role' => 'farmer',
    ]);

    $farmer = FarmerProfile::create([
        'user_id' => $farmerUser->id,
        'stall_name' => 'Test Farm',
        'contact_person' => 'Test Farmer',
        'contact_number' => '123456789',
        'address' => 'Test Address',
        'status' => 'approved',
    ]);

    $category = Category::create([
        'name' => 'Test Category',
    ]);

    return Product::create([
        'farmer_profile_id' => $farmer->id,
        'category_id' => $category->id,
        'name' => 'Test Product',
        'price' => 10.00,
        'stock_quantity' => 5,
        'is_available' => true,
    ]);
}

test('customer can add a product to favorites', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $product = createTestProduct();

    $response = $this->actingAs($customer)
        ->post(route('customer.favorites.products.store', $product));

    $response->assertRedirect();

    $this->assertDatabaseHas('favorites', [
        'user_id' => $customer->id,
        'product_id' => $product->id,
    ]);
});

test('customer can remove a product from favorites', function () {
    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $product = createTestProduct();

    Favorite::create([
        'user_id' => $customer->id,
        'product_id' => $product->id,
    ]);

    $response = $this->actingAs($customer)
        ->delete(route('customer.favorites.products.destroy', $product));

    $response->assertRedirect();

    $this->assertDatabaseMissing('favorites', [
        'user_id' => $customer->id,
        'product_id' => $product->id,
    ]);
});

test('customer cannot remove another customers product favorite', function () {
    $customer1 = User::factory()->create([
        'role' => 'customer',
    ]);

    $customer2 = User::factory()->create([
        'role' => 'customer',
    ]);

    $product = createTestProduct();

    Favorite::create([
        'user_id' => $customer1->id,
        'product_id' => $product->id,
    ]);

    $this->actingAs($customer2)
        ->delete(route('customer.favorites.products.destroy', $product));

    $this->assertDatabaseHas('favorites', [
        'user_id' => $customer1->id,
        'product_id' => $product->id,
    ]);
});
function createTestFarmer(): FarmerProfile
{
    $farmerUser = User::factory()->create(['role' => 'farmer']);

    return FarmerProfile::create([
        'user_id' => $farmerUser->id,
        'stall_name' => 'Test Farmer Stall',
        'contact_person' => 'Test Farmer',
        'contact_number' => '123456789',
        'address' => 'Test Address',
        'status' => 'approved',
    ]);
}

function createTestMarket(): Market
{
    return Market::create([
        'name' => 'Test Market',
        'address' => 'Test Market Address',
    ]);
}
test('customer can add a farmer to favorites', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    $farmer = createTestFarmer();

    $response = $this->actingAs($customer)
        ->post(route('customer.favorites.farmers.store', $farmer));

    $response->assertRedirect();

    $this->assertDatabaseHas('favorites', [
        'user_id' => $customer->id,
        'farmer_profile_id' => $farmer->id,
    ]);
});

test('customer can remove a farmer from favorites', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    $farmer = createTestFarmer();

    Favorite::create([
        'user_id' => $customer->id,
        'farmer_profile_id' => $farmer->id,
    ]);

    $response = $this->actingAs($customer)
        ->delete(route('customer.favorites.farmers.destroy', $farmer));

    $response->assertRedirect();

    $this->assertDatabaseMissing('favorites', [
        'user_id' => $customer->id,
        'farmer_profile_id' => $farmer->id,
    ]);
});

test('customer cannot remove another customers farmer favorite', function () {
    $customer1 = User::factory()->create(['role' => 'customer']);
    $customer2 = User::factory()->create(['role' => 'customer']);
    $farmer = createTestFarmer();

    Favorite::create([
        'user_id' => $customer1->id,
        'farmer_profile_id' => $farmer->id,
    ]);

    $this->actingAs($customer2)
        ->delete(route('customer.favorites.farmers.destroy', $farmer));

    $this->assertDatabaseHas('favorites', [
        'user_id' => $customer1->id,
        'farmer_profile_id' => $farmer->id,
    ]);
});

test('customer can add a market to favorites', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    $market = createTestMarket();

    $response = $this->actingAs($customer)
        ->post(route('customer.favorites.markets.store', $market));

    $response->assertRedirect();

    $this->assertDatabaseHas('favorites', [
        'user_id' => $customer->id,
        'market_id' => $market->id,
    ]);
});

test('customer can remove a market from favorites', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    $market = createTestMarket();

    Favorite::create([
        'user_id' => $customer->id,
        'market_id' => $market->id,
    ]);

    $response = $this->actingAs($customer)
        ->delete(route('customer.favorites.markets.destroy', $market));

    $response->assertRedirect();

    $this->assertDatabaseMissing('favorites', [
        'user_id' => $customer->id,
        'market_id' => $market->id,
    ]);
});

test('customer cannot remove another customers market favorite', function () {
    $customer1 = User::factory()->create(['role' => 'customer']);
    $customer2 = User::factory()->create(['role' => 'customer']);
    $market = createTestMarket();

    Favorite::create([
        'user_id' => $customer1->id,
        'market_id' => $market->id,
    ]);

    $this->actingAs($customer2)
        ->delete(route('customer.favorites.markets.destroy', $market));

    $this->assertDatabaseHas('favorites', [
        'user_id' => $customer1->id,
        'market_id' => $market->id,
    ]);
});
test('customer favorites page displays their own product farmer and market favorites', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $product = createTestProduct();
    $farmer = createTestFarmer();
    $market = createTestMarket();

    Favorite::create([
        'user_id' => $customer->id,
        'product_id' => $product->id,
    ]);

    Favorite::create([
        'user_id' => $customer->id,
        'farmer_profile_id' => $farmer->id,
    ]);

    Favorite::create([
        'user_id' => $customer->id,
        'market_id' => $market->id,
    ]);

    $response = $this->actingAs($customer)
        ->get(route('customer.favorites.index'));

    $response->assertOk()
        ->assertViewHas('productFavorites', function ($favorites) use ($product) {
            return $favorites->contains('product_id', $product->id);
        })
        ->assertViewHas('farmerFavorites', function ($favorites) use ($farmer) {
            return $favorites->contains('farmer_profile_id', $farmer->id);
        })
        ->assertViewHas('marketFavorites', function ($favorites) use ($market) {
            return $favorites->contains('market_id', $market->id);
        });
});

test('customer favorites page does not include another customers favorites', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    $otherCustomer = User::factory()->create(['role' => 'customer']);

    $product = createTestProduct();

    Favorite::create([
        'user_id' => $otherCustomer->id,
        'product_id' => $product->id,
    ]);

    $response = $this->actingAs($customer)
        ->get(route('customer.favorites.index'));

    $response->assertOk()
        ->assertViewHas('productFavorites', function ($favorites) {
            return $favorites->isEmpty();
        })
        ->assertViewHas('farmerFavorites', function ($favorites) {
            return $favorites->isEmpty();
        })
        ->assertViewHas('marketFavorites', function ($favorites) {
            return $favorites->isEmpty();
        });
});

test('customer favorites page returns empty collections when customer has no favorites', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($customer)
        ->get(route('customer.favorites.index'));

    $response->assertOk()
        ->assertViewHas('productFavorites', fn ($favorites) => $favorites->isEmpty())
        ->assertViewHas('farmerFavorites', fn ($favorites) => $favorites->isEmpty())
        ->assertViewHas('marketFavorites', fn ($favorites) => $favorites->isEmpty());
});

test('favorited customer receives a notification when product is restocked', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    $product = createTestProduct();

    Favorite::create([
        'user_id' => $customer->id,
        'product_id' => $product->id,
    ]);

    // Set stock to zero before monitoring notifications.
    $product->update(['stock_quantity' => 0]);

    Notification::fake();

    // Restock the product.
    $product->update(['stock_quantity' => 5]);

    Notification::assertSentTo(
        $customer,
        ProductRestockedNotification::class
    );
});

test('no restock notification is sent when stock remains above zero', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    $product = createTestProduct();

    Favorite::create([
        'user_id' => $customer->id,
        'product_id' => $product->id,
    ]);

    Notification::fake();

    // Stock changes from 5 to 10, not from zero to positive.
    $product->update(['stock_quantity' => 10]);

    Notification::assertNothingSent();
});

test('customer who did not favorite the product receives no restock notification', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    $product = createTestProduct();

    $product->update(['stock_quantity' => 0]);

    Notification::fake();

    $product->update(['stock_quantity' => 5]);

    Notification::assertNotSentTo(
        $customer,
        ProductRestockedNotification::class
    );
});

test('restock notification contains the expected product data', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    $product = createTestProduct();

    Favorite::create([
        'user_id' => $customer->id,
        'product_id' => $product->id,
    ]);

    $product->update(['stock_quantity' => 0]);

    Notification::fake();

    $product->update(['stock_quantity' => 5]);

    Notification::assertSentTo(
        $customer,
        ProductRestockedNotification::class,
        function ($notification, $channels) use ($product, $customer) {
            $data = $notification->toDatabase($customer);

            return in_array('database', $channels)
                && $data['type'] === 'product_restocked'
                && $data['product_id'] === $product->id
                && $data['product_name'] === $product->name
                && $data['message'] === $product->name.' is back in stock.'
                && $data['url'] === route('customer.products.index');
        }
    );
});
