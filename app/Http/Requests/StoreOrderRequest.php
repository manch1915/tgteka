<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'channel_id' => ['required', 'exists:channels,id'],
            'pattern_id' => ['required', 'exists:patterns,id'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:pending,accepted,declined'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
