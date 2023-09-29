<?php

namespace App\Services;

use App\Models\User;

class ReferralBonusService
{
    public function calculateReferralBonus(User $referee): int
    {
        // Fetch all the purchases made by the referral
        $purchases = $referee->purchases;

        // Assuming that you have a setting for the referral percentage
        $referral_percentage = config('app.referral_bonus_percentage');

        $referralBonus = 0;

        foreach ($purchases as $purchase) {
            $referralBonus += $purchase->amount * ($referral_percentage / 100);
        }

        return $referralBonus;
    }
}
