<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'invoice_no' => 'INV-'.strtoupper(uniqid()),
            'subtotal' => 100,
            'shipping' => 60,
            'discount' => 0,
            'total' => 160,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'delivery_status' => 'pending',
        ];
    }
}
