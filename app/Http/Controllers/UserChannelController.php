<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChannelRequest;
use App\Http\Requests\UpdateChannelRequest;
use App\Models\Channel;
use App\Services\AvatarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class UserChannelController extends Controller
{
    public function index()
    {
        return inertia('Dashboard/Channels');
    }

    public function channelsGet(Request $request, AvatarService $avatarService)
    {
        $search = $request->input('search');

        $channels = auth()->user()->channels()->when($search, function ($query, $search) {
            return $query->where('channel_name', 'like', '%'.$search.'%');
        })->orderBy('created_at', 'desc')->paginate(10);


        $channels->each(function ($channel) use ($avatarService) {
            $channel->avatar = $avatarService->getAvatarUrlOfChannel($channel);
            $channel->pending_order_count = $channel->orders()->where('status', 'pending')->count();
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
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        unset($validated['terms']);

        Channel::create($validated);

        return response()->json('success');
    }

    public function update(UpdateChannelRequest $request, Channel $channel)
    {
        $validated = $request->validated();

        unset($validated['terms']);
        $channel->update($validated);

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
