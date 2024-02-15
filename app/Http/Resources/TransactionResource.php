<?php

namespace App\Http\Resources;

use App\Services\DateLocalizationService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Transaction */
class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'amount' => $this->amount,
            'appointment' => $this->appointment,
            'service' => $this->service,
            'transaction_id' => $this->transaction_id,
            'status' => __('messages.' . $this->status),
            'payment_method' => $this->payment_method,
            'details' => $this->details,
            'created_at' => DateLocalizationService::localize($this->created_at) . ', ' . $this->created_at->format('H:i'),
        ];
    }
}
