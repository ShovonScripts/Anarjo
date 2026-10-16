<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function view(?User $user, Order $order): bool
    {
        if ($order->user_id === null) {
            return $this->guestTokenMatches($order);
        }

        return $user !== null && $order->user_id === $user->id;
    }

    public function pay(?User $user, Order $order): bool
    {
        if ($order->user_id === null) {
            return $this->guestTokenMatches($order);
        }

        return $user !== null && $order->user_id === $user->id;
    }

    private function guestTokenMatches(Order $order): bool
    {
        $token = request()->query('token') ?? request()->input('token');

        return is_string($token)
            && $order->confirmation_token !== null
            && hash_equals($order->confirmation_token, $token);
    }
}
