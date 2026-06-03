<?php

namespace App\Jobs;

use App\Models\Order;
use App\Notifications\OrderConfirmation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendOrderConfirmationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order
    ) {}

    public function handle(): void
    {
        if ($this->order->user && $this->order->user->email) {
            $this->order->user->notify(new OrderConfirmation($this->order));
        } elseif ($this->order->guest_email) {
            $notifiable = new \Illuminate\Notifications\AnonymousNotifiable;
            $notifiable->route('mail', $this->order->guest_email);
            $notifiable->notify(new OrderConfirmation($this->order));
        }
    }
}
