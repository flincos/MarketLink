<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderReadyForPickup extends Notification
{
    use Queueable;

    public function __construct(public Order $order)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database']; // add 'mail' later if needed
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your order is ready for pickup')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your order #' . $this->order->id . ' is now ready for pickup.')
            ->line('Pickup details:')
            ->line('Market: ' . optional($this->order->market)->name)
            ->line('Pickup date/time: ' . ($this->order->pickup_time ?? 'See order details'))
            ->action('View Order', url('/customer/orders/' . $this->order->id))
            ->line('Please remember to pay at pickup. Thank you!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'         => 'order_ready_for_pickup',
            'order_id'     => $this->order->id,
            'order_status' => $this->order->status,
            'message'      => 'Your order #' . $this->order->id . ' is ready for pickup.',
            'market'       => optional($this->order->market)->name,
            'pickup_time'  => $this->order->pickup_time ?? null,
        ];
    }
}
