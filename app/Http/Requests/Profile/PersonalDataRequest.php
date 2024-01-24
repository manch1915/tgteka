<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonalDataRequest extends FormRequest
{
    public function rules(): array
    {
        $uniqueRule = Rule::unique('users')->ignore(auth()->id());

        return [
            'username' => $this->generateNameRule($uniqueRule),
            'mobile_number' => $this->generateMobileNumberRule($uniqueRule)
        ];
    }

    private function generateNameRule($uniqueRule): array
    {
        return ['required' ,'alpha', 'min:5', 'max:16', $uniqueRule];
    }

    private function generateMobileNumberRule($uniqueRule): array
    {
        return ['nullable', 'regex:/^\+7\s\(\d{3}\)\s\d{3}-\d{2}-\d{2}$/', $uniqueRule];
    }

    public function authorize(): bool
    {
        return true;
    }
}
