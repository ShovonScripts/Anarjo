<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;

class OrderDetail extends Component
{
    public Order $order;

    public string $payment_status = '';
    public string $delivery_status = '';
    public string $notes = '';

    public function mount(): void
    {
        $this->payment_status = $this->order->payment_status;
        $this->delivery_status = $this->order->delivery_status;
        $this->notes = $this->order->notes ?? '';
    }

    public function updateStatus()
    {
        $this->validate([
            'payment_status' => 'required|in:pending,completed,cancelled,failed',
            'delivery_status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $this->order->update([
            'payment_status' => $this->payment_status,
            'delivery_status' => $this->delivery_status,
            'notes' => $this->notes,
        ]);

        session()->flash('message', 'Order status updated.');
    }

    public function render()
    {
        $this->order->load(['user', 'items.book', 'address', 'coupon']);

        return view('livewire.admin.order-detail', ['order' => $this->order])
            ->layout('layouts.admin');
    }
}
