<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\Cache\CacheService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessOrderJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order
    ) {}

    public function handle(): void
    {
        // Reduce inventory
        foreach ($this->order->items as $item) {
            if ($item->book) {
                $item->book->decrement('stock', $item->qty);
            }
        }

        // Clear relevant caches
        CacheService::flushBooks();

        // TODO: Send email notification
        // TODO: Generate invoice PDF
        // TODO: Send SMS notification
    }
}
