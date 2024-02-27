<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChannelRequest;
use App\Http\Requests\UpdateChannelRequest;
use App\Models\Channel;
use App\Services\AvatarService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class UserChannelController extends Controller
{
    use ValidatesRequests;
    public function index()
    {
        return inertia('Dashboard/Channels');
    }

    public function channelsGet(Request $request, AvatarService $avatarService)
    {
        $search = $request->input('search');

        $channelsQuery = auth()->user()->channels()->when($search, function ($query, $search) {
            return $query->where('channel_name', 'like', '%'.$search.'%');
        });

        $tenthChannel = Channel::where('status', 'accepted')->orderBy('rating', 'desc')->skip(9)->first();
        $tenthChannelRating = $tenthChannel ? $tenthChannel->rating : 0;

        $channels = $channelsQuery->orderBy('created_at', 'desc')->paginate(10);

        $channels->load(['orders' => function ($query) {
            $query->where('status', 'pending');
        }]);

        $allChannelsAboveRating = Channel::where('status', 'accepted')->where('rating', '>', $channels->min('rating'))->count();

        $channels->each(function ($channel) use ($avatarService, $tenthChannelRating, $allChannelsAboveRating) {
            $channel->avatar = $avatarService->getAvatarUrlOfChannel($channel);
            $channel->pending_order_count = $channel->orders->count();
            $channel->rating_diff = max($tenthChannelRating - $channel->rating, 0);

            // Calculate channel's position based on all accepted channels
            $channel->position = $allChannelsAboveRating + 1; // pre-calculate count for better performance

            return $channel;
        });

        return response()->json($channels);
    }

    public function show()
    {
        return inertia('Dashboard/AddingChannel');
    }

    public function store(StoreChannelRequest $request)
    {
        Channel::create([
                'user_id' => auth()->id(),
            ] + $request->except('terms'));

        return response()->json('success');
    }

    public function update(UpdateChannelRequest $request, Channel $channel)
    {
        $channel->update($request->except('terms'));

        return response()->json($channel);
    }

    public function edit(Channel $channel)
    {
        return inertia('Dashboard/EditChannel', [
            'channelId' => $channel->id,
            'channel' => $channel
        ]);
    }
}
