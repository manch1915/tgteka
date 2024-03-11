<?php

namespace App\Http\Resources;

use App\Services\DateLocalizationService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\SupportTicket */
class SupportTicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sender_id' => $this->sender_id,
            'title' => $this->title,
            'created_at' => DateLocalizationService::localize($this->created_at) . ', ' . $this->created_at->format('H:i'),
            'updated_at' => $this->updated_at,
        ];
    }
}
