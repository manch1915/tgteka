<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CallbackRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:25'],
            'email' => ['required', 'email', 'max:250'],
            'terms' => ['accepted']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
