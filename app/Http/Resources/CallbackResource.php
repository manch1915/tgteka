<?php

namespace App\Http\Resources;

use App\Services\DateLocalizationService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Callback */
class CallbackResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'mobile_number' => $this->mobile_number,
            'status' => trans('statuses.' . $this->status),
            'created_at' => DateLocalizationService::localize($this->created_at) . ', ' . $this->created_at->format('H:i'),
        ];
    }
}
