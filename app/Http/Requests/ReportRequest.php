<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'report_message' => 'string|max:300|required',
            'order_id' => 'int|required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
