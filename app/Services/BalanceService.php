<?php

namespace App\Services;

use App\Models\User;

class BalanceService
{
    public function addToUserBalance(User $user, float $amount): void
    {
        $user->balance += $amount;
        $user->save();
    }
}
