<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'order_id' => ['required', 'exists:orders,id'],
            'review_text' => ['required', 'string','max:300'],
            'rating' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
