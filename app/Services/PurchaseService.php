<?php

namespace App\Services;

use App\Models\User;

class PurchaseService
{

    protected $referralBonusService;

    public function __construct(ReferralBonusService $referralBonusService)
    {
        $this->referralBonusService = $referralBonusService;
    }

    public function purchase(Purchase $purchase): void
    {
        // Here write logic to make purchase

        // After purchase is done, calculate the referral bonus
        if ($purchase->user->referral_id) {
            $bonusAmount = $this->referralBonusService->calculateReferralBonus($purchase->user);
            // Update balance of referrer
            $referrer = User::findOrFail($purchase->user->referral_id);
            $referrer->wallet_balance += $bonusAmount;
            $referrer->save();
        }
    }
}
