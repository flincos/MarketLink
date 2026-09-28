<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductRestocked extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Product $product)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database']; // add 'mail' later if desired
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('A favorite product is back in stock')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('The product "' . $this->product->name . '" is now back in stock.')
            ->action('View Product', url('/customer/products/' . $this->product->id))
            ->line('You added this product to your favorites.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'product_restocked',
            'product_id' => $this->product->id,
            'message'    => 'A favorite product "' . $this->product->name . '" is back in stock.',
        ];
    }
}