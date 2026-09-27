<?php

use App\Models\FarmerProfile;
use App\Models\Market;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PickupSlot;
use App\Models\Product;
use App\Models\User;
use App\Notifications\OrderConfirmed;
use App\Notifications\OrderReadyForPickup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Models\Category;
uses(RefreshDatabase::class);

function makeFarmerOrderScenario(string $status = 'placed', int $stock = 3, int $quantity = 2): array
{
    $farmerUser = User::factory()->create([
        'role' => 'farmer',
    ]);

    $farmer = FarmerProfile::create([
        'user_id' => $farmerUser->id,
        'stall_name' => 'Test Farmer',
        'contact_person' => 'Test Contact',
        'contact_number' => '03000000000',
        'address' => 'Test Address',
        'operating_days' => ['Monday'],
        'status' => 'approved',
    ]);

    $customer = User::factory()->create([
        'role' => 'customer',
    ]);

    $market = Market::create([
        'name' => 'Test Market',
        'address' => 'Test Market Address',
        'latitude' => 0,
        'longitude' => 0,
        'description' => 'Test market',
    ]);

    $farmer->markets()->attach($market->id);

    $pickupSlot = PickupSlot::create([
        'farmer_profile_id' => $farmer->id,
        'market_id' => $market->id,
        'date' => today(),
        'start_time' => '10:00:00',
        'end_time' => '12:00:00',
        'capacity' => 10,
        'is_available' => true,
    ]);

   $category = Category::create([
    'name' => 'Test Category',
    'description' => 'Test category',
]);

$product = Product::create([
    'farmer_profile_id' => $farmer->id,
    'category_id' => $category->id,
    'name' => 'Test Product',
    'description' => 'Test product',
    'price' => 100,
    'unit' => 'kg',
    'stock_quantity' => $stock,
    'is_available' => true,
    'is_hidden' => false,
]);

    $order = Order::create([
        'customer_id' => $customer->id,
        'farmer_profile_id' => $farmer->id,
        'market_id' => $market->id,
        'pickup_slot_id' => $pickupSlot->id,
        'pickup_date' => today(),
        'pickup_time' => '10:00:00',
        'total_amount' => 100 * $quantity,
        'status' => $status,
        'notes' => null,
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
        'price' => $product->price,
        'quantity' => $quantity,
        'subtotal' => $product->price * $quantity,
    ]);

    return compact(
        'farmerUser',
        'farmer',
        'customer',
        'market',
        'pickupSlot',
        'product',
        'order'
    );
}

test('farmer can accept a placed order', function () {
    Notification::fake();

    $scenario = makeFarmerOrderScenario();

    $response = $this->actingAs($scenario['farmerUser'])->patch(
        route('farmer.orders.update-status', $scenario['order']),
        ['status' => 'accepted']
    );

    $response
        ->assertRedirect(route('farmer.orders.show', $scenario['order']));

    expect($scenario['order']->refresh()->status)->toBe('accepted');

    Notification::assertSentTo(
        $scenario['customer'],
        OrderConfirmed::class
    );
});

test('farmer can decline a placed order and reserved stock is restored', function () {
    Notification::fake();

    $scenario = makeFarmerOrderScenario(
        status: 'placed',
        stock: 3,
        quantity: 2
    );

    $response = $this->actingAs($scenario['farmerUser'])->patch(
        route('farmer.orders.update-status', $scenario['order']),
        ['status' => 'declined']
    );

    $response
        ->assertRedirect(route('farmer.orders.show', $scenario['order']));

    expect($scenario['order']->refresh()->status)->toBe('declined');
    expect($scenario['product']->refresh()->stock_quantity)->toEqual(5);
});

test('farmer can mark an accepted order as ready and customer is notified', function () {
    Notification::fake();

    $scenario = makeFarmerOrderScenario(
        status: 'accepted'
    );

    $response = $this->actingAs($scenario['farmerUser'])->patch(
        route('farmer.orders.update-status', $scenario['order']),
        ['status' => 'ready']
    );

    $response
        ->assertRedirect(route('farmer.orders.show', $scenario['order']));

    expect($scenario['order']->refresh()->status)->toBe('ready');

    Notification::assertSentTo(
        $scenario['customer'],
        OrderReadyForPickup::class
    );
});

test('farmer can mark a ready order as completed', function () {
    $scenario = makeFarmerOrderScenario(
        status: 'ready'
    );

    $response = $this->actingAs($scenario['farmerUser'])->patch(
        route('farmer.orders.update-status', $scenario['order']),
        ['status' => 'completed']
    );

    $response
        ->assertRedirect(route('farmer.orders.show', $scenario['order']));

    expect($scenario['order']->refresh()->status)->toBe('completed');
});

test('invalid farmer order status transitions are rejected', function () {
    $scenario = makeFarmerOrderScenario(
        status: 'accepted'
    );

    $response = $this->actingAs($scenario['farmerUser'])->patch(
        route('farmer.orders.update-status', $scenario['order']),
        ['status' => 'declined']
    );

    $response
        ->assertRedirect(route('farmer.orders.show', $scenario['order']));

    expect($scenario['order']->refresh()->status)->toBe('accepted');
});

test('farmer cannot modify another farmers order', function () {
    $scenario = makeFarmerOrderScenario();

    $otherFarmerUser = User::factory()->create([
        'role' => 'farmer',
    ]);

    FarmerProfile::create([
        'user_id' => $otherFarmerUser->id,
        'stall_name' => 'Other Farmer',
        'contact_person' => 'Other Contact',
        'contact_number' => '03111111111',
        'address' => 'Other Address',
        'operating_days' => ['Tuesday'],
        'status' => 'approved',
    ]);

    $response = $this->actingAs($otherFarmerUser)->patch(
        route('farmer.orders.update-status', $scenario['order']),
        ['status' => 'accepted']
    );

    $response->assertForbidden();

    expect($scenario['order']->refresh()->status)->toBe('placed');
});