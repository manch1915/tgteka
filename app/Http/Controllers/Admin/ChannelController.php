<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function index()
    {
        $channels = Channel::all();
        $channels->each(function ($channel) {
            $media = $channel->getMedia('avatars')->last();

            if ($media) {
                $channel->avatar = $media->getUrl();
            } else {
                $channel->avatar = 'https://avatars.dicebear.com/api/bottts/' . $channel->channel_name. '.svg';
            }

            return $channel;
        });
        [$channelsType, $chatsType] = $channels->partition(function ($channel) {
            return $channel->type == 'channel';
        });

        return response()->json([
            'channels' => $channelsType,
            'chats' => $chatsType,
        ]);
    }

    public function store(Request $request)
    {
    }

    public function show(Channel $channel)
    {
    }

    public function update(Request $request, Channel $channel)
    {
        $channel->update($request->all());
        return response()->json();

    }

    public function destroy(Channel $channel)
    {
        $channel->delete();
        return response()->json();
    }
}
