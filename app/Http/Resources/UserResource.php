<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'mobile_number' => $this->mobile_number,
            'telegram_username' => $this->telegram_username,
            'balance' => $this->balance,
            'channels_count' => $this->channels_count,
            'orders_count' => $this->orders_count,
            'patterns_count' => $this->patterns_count,
            'is_moderator' => $this->hasRole('Moderator'),
        ];
    }
}
