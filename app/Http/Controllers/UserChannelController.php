<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChannelRequest;
use App\Http\Requests\UpdateChannelRequest;
use App\Models\Channel;
use App\Services\AvatarService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

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

        $channels = $channelsQuery->with(['orders' => function ($query) {
            $query->where('status', 'pending');
        }])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $allChannelsAboveRating = Channel::where('status', 'accepted')
            ->where(function ($query) use ($channels) {
                $minRating = $channels->min('rating');
                if ($minRating !== null) {
                    $query->where('rating', '>', $minRating);
                }
            })
            ->count();

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
        $validated = $request->validated();

        unset($validated['terms']);

        $validated['status'] = 'pending';

        $channel->update($validated);

        return response()->json($channel);
    }

    public function edit(Channel $channel)
    {
        $this->authorize('edit', $channel);

        return inertia('Dashboard/EditChannel', [
            'channelId' => $channel->id,
            'channel' => $channel
        ]);
    }
}
