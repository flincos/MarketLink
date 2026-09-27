<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderConfirmed extends Notification
{
    use Queueable;

    public function __construct(public Order $order)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // database for in-app, mail optional
        return ['database']; // add 'mail' if you later configure email
    }

    /**
     * Get the mail representation of the notification (optional).
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your order has been confirmed')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your order #' . $this->order->id . ' has been confirmed by the farmer.')
            ->line('Pickup details:')
            ->line('Market: ' . optional($this->order->market)->name)
            ->line('Pickup date/time: ' . ($this->order->pickup_time ?? 'See order details'))
            ->action('View Order', url('/customer/orders/' . $this->order->id))
            ->line('Thank you for using MarketLink!');
    }

    /**
     * Get the array representation for database notifications.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type'         => 'order_confirmed',
            'order_id'     => $this->order->id,
            'order_status' => $this->order->status,
            'message'      => 'Your order #' . $this->order->id . ' has been confirmed.',
            'market'       => optional($this->order->market)->name,
            'pickup_time'  => $this->order->pickup_time ?? null,
        ];
    }
}
