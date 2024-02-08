<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Order */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'price' => $this->price,
            'status' => trans('statuses.' . $this->status),
            'post_date' => $this->post_date,
            'post_date_end' => $this->post_date_end,
            'decline_reason' => $this->decline_reason,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'order_reports_count' => $this->order_reports_count,

            'channel' => new ChannelResource($this->whenLoaded('channel')),
            'order' => new OrderResource($this->whenLoaded('order')),
            'user' => new UserResource($this->whenLoaded('user')),
            'topic' => $this->whenLoaded('topic')
        ];
    }
}
