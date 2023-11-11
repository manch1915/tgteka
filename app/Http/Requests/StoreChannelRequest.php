<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChannelRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'avatar'             => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
            'channel_name'       => 'required|max:200',
            'description'        => 'required|string',
            'subscribers_source' => 'required|string',
            'topic'              => 'required',
            'type'               => 'required|in:channel,chat',
            'channel_url'        => ['required', 'regex:/^@(?:[a-z0-9\_]+)$|^(https\:\/\/t\.me\/[a-zA-Z0-9\_\+\-]+)$/i'],
            'language'           => 'required|in:english,russian',
            'repeat_discount'    => 'nullable|in:10,20,30,50',
            'terms'              => 'accepted',
            'format_one'         => 'required|integer',
            'format_two'         => 'nullable|integer',
            'format_three'       => 'nullable|integer',
            'no_deletion'        => 'nullable|integer',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
