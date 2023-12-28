<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CallbackRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:25'],
            'mobile_number' => ['required', 'string', 'min:16', 'max:16', 'unique:users', 'regex:/\+\d{1}\s\d{3}\s\d{3}-\d{2}-\d{2}/'],
            'terms' => ['accepted']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
