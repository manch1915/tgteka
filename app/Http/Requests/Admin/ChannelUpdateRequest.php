<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ChannelUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'url' => ['required'],
            'channel_name' => ['required'],
            'topic_id' => ['required', 'exists:channels,topic_id'],
            'description' => ['nullable'],
            'subscribers_source' => ['nullable'],
            'format_one_price' => ['required', 'integer'],
            'format_two_price' => ['required', 'integer'],
            'format_three_price' => ['required', 'integer'],
            'no_deletion_price' => ['required', 'integer'],
            'male_percentage' => ['required', 'numeric'],
            'score' => ['required', 'numeric'],
            'rating' => ['required', 'numeric'],
            'likes_count' => ['required', 'integer'],
            'views_count' => ['required', 'integer'],
            'status' => ['required'],
            'channel_creation_date' => ['nullable', 'date'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
