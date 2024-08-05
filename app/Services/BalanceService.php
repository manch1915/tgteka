<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;

class BalanceService
{
    public function addToUserBalance(User $user, float $amount): void
    {
        $user->balance += $amount;
        $user->save();
    }

    public function refundUser(Order $order): void
    {
        $user = $order->user;
        $price = $order->price;

        // Assuming the User model has a method to update balance
        $user->refundBalance($price);
    }
}
