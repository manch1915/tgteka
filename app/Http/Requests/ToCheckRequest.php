<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ToCheckRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'post_link' => ['required', 'regex:/^https:\/\/t\.me\/(?:[a-zA-Z0-9_-]+\/\d+|c\/\d+\/\d+)$/'],
            'orderId' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
