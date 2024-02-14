<?php

namespace App\Http\Controllers\Admin;

use App\Console\Commands\UpdateChannelStatistics;
use App\Http\Controllers\Controller;
use App\Jobs\FetchChannelStatisticsJob;
use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function index()
    {
        $channels = Channel::with('topic')->get();
        $channels->map(function ($channel) {
            $media = $channel->getMedia('avatars')->last();
            if ($media) {
                $channel->avatar = $media->getUrl();
            } else {
                $channel->avatar = 'https://api.dicebear.com/7.x/initials/svg?seed=' . $channel->channel_name;
            }
            return $channel;
        });
        [$channelsType, $chatsType] = $channels->partition(function ($channel) {
            return $channel->type === 'channel';
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
        $inputData = $request->all();

        $channel->update($inputData);

        if($channel->status === 'loading'){
            FetchChannelStatisticsJob::dispatch($channel);
        }

        return response()->json();
    }

    public function destroy(Channel $channel)
    {
        $channel->delete();
        return response()->json();
    }
}
