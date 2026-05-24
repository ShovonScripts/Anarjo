<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderConfirmation extends Notification
{
    use Queueable;

    public function __construct(
        public Order $order
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Order Confirmation - ' . $this->order->invoice_no)
            ->greeting('Dear ' . $notifiable->name . ',')
            ->line('Your order has been placed successfully.')
            ->line('Invoice: ' . $this->order->invoice_no)
            ->line('Total: ৳' . number_format($this->order->total))
            ->action('View Order', route('order.confirmation', $this->order))
            ->line('Thank you for shopping with Anarjo Publications!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'invoice_no' => $this->order->invoice_no,
            'total' => $this->order->total,
        ];
    }
}
