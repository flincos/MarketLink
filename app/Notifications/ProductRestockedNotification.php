<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProductRestockedNotification extends Notification
{
    use Queueable;

    protected Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Determine the notification delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the notification data for database storage.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'product_restocked',
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'message' => $this->product->name.' is back in stock.',
            'url' => route('customer.products.index'),
        ];
    }
}
