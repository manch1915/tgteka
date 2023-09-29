<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatternRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'media' => ['required', 'string'],
            'body' => ['required', 'string'],
            'status' => ['required', 'in:pending,accepted,declined'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
