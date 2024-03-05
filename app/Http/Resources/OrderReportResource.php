<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\OrderReport */
class OrderReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'report' => [
                'id' => $this->id,
                'message' => $this->message,
                'status' => $this->status,
            ],
            'order' => $this->whenLoaded('order'),
        ];
    }
}
