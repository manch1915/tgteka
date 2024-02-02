<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Transaction */
class PayoutResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->user->username,
            'appointment' => $this->appointment,
            'service' => $this->service,
            'amount' => $this->amount,
            'transaction_id' => $this->transaction_id,
            'status' => __('messages.' . $this->status),
            'payment_method' => $this->payment_method,
            'details' => $this->details,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
