<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatternRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'media' => ['file', 'nullable'],
            'body' => ['string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
