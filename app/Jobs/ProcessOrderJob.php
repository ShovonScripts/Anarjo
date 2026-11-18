<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\InvoiceService;
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
        // Inventory is reserved atomically during checkout (see Checkout::placeOrder).

        // Generate invoice PDF
        $invoice = app(InvoiceService::class);
        $invoice->generatePdf($this->order);

        // TODO: Send SMS notification
    }
}
