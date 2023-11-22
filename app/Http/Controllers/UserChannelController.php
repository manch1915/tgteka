<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChannelRequest;
use App\Http\Requests\UpdateChannelRequest;
use App\Models\Channel;
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

    public function channelsGet(Request $request)
    {
        $search = $request->input('search');

        $channels = auth()->user()->channels()->when($search, function ($query, $search) {
            return $query->where('channel_name', 'like', '%'.$search.'%');
        })->orderBy('created_at', 'desc')->paginate(10);

        // Add avatar url to each channel object
        $channels->each(function ($channel) {
            $media = $channel->getMedia('avatars')->last();

            if ($media) {
                $channel->avatar_url = $media->getUrl();
            } else {
                $channel->avatar_url = 'https://avatars.dicebear.com/api/bottts/' . $channel->channel_name. '.svg';
            }

            return $channel;
        });

        return response()->json($channels);
    }

    public function show()
    {
        return inertia('Dashboard/AddingChannel');
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function store(StoreChannelRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        unset($validated['avatar'], $validated['terms']);
        $channel = Channel::create($validated);

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $channel->addMedia($avatar)->toMediaCollection('avatars');
        }

        return response()->json('success');
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function update(UpdateChannelRequest $request, Channel $channel)
    {
        $validated = $request->validated();

        if($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $channel->addMedia($avatar)->toMediaCollection('avatars');
            unset($validated['avatar']);
        }

        unset($validated['terms']);
        $channel->update($validated);

        return response()->json($channel);
    }

    public function edit(Channel $channel)
    {
        $channelAvatar = $channel->getMedia('avatars')
            ->last()
            ->getUrl() ?? 'https://avatars.dicebear.com/api/bottts/' . $channel->channel_name. '.svg';

        return inertia('Dashboard/EditChannel', [
            'channelId' => $channel->id,
            'channel' => $channel,
            'channelAvatar' => $channelAvatar,
        ]);
    }
}
