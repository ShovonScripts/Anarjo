<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class Orders extends Component
{
    use WithPagination;

    public $search = '';
    public $paymentFilter = '';
    public $deliveryFilter = '';

    public function render()
    {
        $query = Order::with('user');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('invoice_no', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%"));
            });
        }

        if ($this->paymentFilter) {
            $query->where('payment_status', $this->paymentFilter);
        }

        if ($this->deliveryFilter) {
            $query->where('delivery_status', $this->deliveryFilter);
        }

        $orders = $query->latest()->paginate(15);

        return view('livewire.admin.orders', compact('orders'))
            ->layout('layouts.admin');
    }
}
