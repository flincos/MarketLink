<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

function createTestNotification(User $user, array $data = [], ?Carbon $readAt = null)
{
    return $user->notifications()->create([
        'id' => (string) Str::uuid(),
        'type' => 'App\\Notifications\\ProductRestockedNotification',
        'data' => array_merge([
            'type' => 'product_restocked',
            'product_id' => 1,
            'product_name' => 'Test Product',
            'message' => 'Test Product is back in stock.',
            'url' => '/customer/products',
        ], $data),
        'read_at' => $readAt,
    ]);
}

test('customer can view their notifications', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $notification = createTestNotification($customer);

    $response = $this->actingAs($customer)
        ->get(route('customer.notifications.index'));

    $response->assertOk()
        ->assertViewHas('notifications', function ($notifications) use ($notification) {
            return $notifications->contains('id', $notification->id);
        });
});

test('customer cannot see another customers notifications', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    $otherCustomer = User::factory()->create(['role' => 'customer']);

    $otherNotification = createTestNotification($otherCustomer);

    $response = $this->actingAs($customer)
        ->get(route('customer.notifications.index'));

    $response->assertOk()
        ->assertViewHas('notifications', function ($notifications) use ($otherNotification) {
            return ! $notifications->contains('id', $otherNotification->id);
        });
});

test('customer can mark their notification as read', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $notification = createTestNotification($customer);

    $response = $this->actingAs($customer)
        ->patch(route('customer.notifications.read', $notification->id));

    $response->assertRedirect();

    $this->assertDatabaseHas('notifications', [
        'id' => $notification->id,
        'notifiable_id' => $customer->id,
    ]);

    expect($notification->fresh()->read_at)->not->toBeNull();
});

test('customer cannot mark another customers notification as read', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    $otherCustomer = User::factory()->create(['role' => 'customer']);

    $notification = createTestNotification($otherCustomer);

    $this->actingAs($customer)
        ->patch(route('customer.notifications.read', $notification->id))
        ->assertNotFound();

    expect($notification->fresh()->read_at)->toBeNull();
});

test('customer can mark all their unread notifications as read', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $unread1 = createTestNotification($customer);
    $unread2 = createTestNotification($customer);

    $response = $this->actingAs($customer)
        ->patch(route('customer.notifications.readAll'));

    $response->assertRedirect();

    expect($unread1->fresh()->read_at)->not->toBeNull()
        ->and($unread2->fresh()->read_at)->not->toBeNull();
});

test('marking all notifications as read does not affect another customers notifications', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    $otherCustomer = User::factory()->create(['role' => 'customer']);

    $otherNotification = createTestNotification($otherCustomer);

    $this->actingAs($customer)
        ->patch(route('customer.notifications.readAll'))
        ->assertRedirect();

    expect($otherNotification->fresh()->read_at)->toBeNull();
});

test('marking all notifications as read leaves already read notifications unchanged', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $readAt = now()->subDay();

    $alreadyRead = createTestNotification($customer, [], $readAt);
    $unread = createTestNotification($customer);

    $this->actingAs($customer)
        ->patch(route('customer.notifications.readAll'))
        ->assertRedirect();

    expect($alreadyRead->fresh()->read_at->toDateTimeString())
        ->toBe($readAt->toDateTimeString())
        ->and($unread->fresh()->read_at)->not->toBeNull();
});
