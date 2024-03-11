<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChannelUpdateRequest;
use App\Jobs\FetchChannelStatisticsJob;
use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function index()
    {
        $channels = Channel::with('topic')->get();

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

    public function edit(Channel $channel)
    {
        return inertia('Admin/TablesShow', ['channel' => $channel]);
    }

    public function update(ChannelUpdateRequest $request, Channel $channel)
    {
        $inputData = $request->validated();

        $channel->update($inputData);

        if($channel->status === 'loading'){
            FetchChannelStatisticsJob::dispatch($channel);
        }

        return response()->json();
    }

    public function destroy(Channel $channel)
    {
        $channel->delete();
        return response()->json(null, 204);
    }
}
