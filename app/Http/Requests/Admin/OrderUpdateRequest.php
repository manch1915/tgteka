<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => 'required|string|in:accepted,declined,pending',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
