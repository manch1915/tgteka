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
            'description'        => 'required|string|max:300',
            'subscribers_source' => 'required|string|max:300',
            'topic_id'           => 'required',
            'type'               => 'required|in:channel,chat',
            'url'                => ['required', 'regex:/^@(?:[a-z0-9\_]+)$|^(https\:\/\/t\.me\/[a-zA-Z0-9\_\+\-]+)$/i', 'unique:channels,url'],
            'language'           => 'required|in:english,russian',
            'repeat_discount'    => 'nullable|in:0,10,20,30,50',
            'male_percentage'    => 'integer|required',
            'terms'              => 'accepted',
            'format_one_price'   => 'required|integer',
            'format_two_price'   => 'nullable|integer',
            'format_three_price' => 'nullable|integer',
            'no_deletion_price'  => 'nullable|integer',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
