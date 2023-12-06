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
            'name' => $this->generateNameRule($uniqueRule),
            'mobile_number' => $this->generateMobileNumberRule($uniqueRule)
        ];
    }

    private function generateNameRule($uniqueRule): array
    {
        return ['nullable', 'alpha', 'min:2', 'max:25', $uniqueRule];
    }

    private function generateMobileNumberRule($uniqueRule): array
    {
        return ['nullable', 'numeric', 'min:10', $uniqueRule];
    }

    public function authorize(): bool
    {
        return true;
    }
}
