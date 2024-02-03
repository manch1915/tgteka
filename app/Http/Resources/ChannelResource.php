<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Channel */
class ChannelResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'url' => $this->url,
            'channel_name' => $this->channel_name,
            'slug' => $this->slug,
            'type' => $this->type,
            'language' => $this->language,
            'description' => $this->description,
            'subscribers_source' => $this->subscribers_source,
            'format_one_price' => $this->format_one_price,
            'format_two_price' => $this->format_two_price,
            'format_three_price' => $this->format_three_price,
            'no_deletion_price' => $this->no_deletion_price,
            'repost_price' => $this->repost_price,
            'repeat_discount' => $this->repeat_discount,
            'male_percentage' => $this->male_percentage,
            'score' => $this->score,
            'rating' => $this->rating,
            'likes_count' => $this->likes_count,
            'views_count' => $this->views_count,
            'status' => $this->status,
            'channel_creation_date' => $this->channel_creation_date,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'favoriters_count' => $this->favoriters_count,
            'favorites_count' => $this->favorites_count,
            'media_count' => $this->media_count,
            'orders_count' => $this->orders_count,
            'reviews_count' => $this->reviews_count,
        ];
    }
}
