<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => ['required'],
            'email' => ['required', 'email', 'max:254'],
            'mobile_number' => ['nullable'],
            'balance' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
