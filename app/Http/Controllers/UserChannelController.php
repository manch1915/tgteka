<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChannelRequest;
use App\Http\Requests\UpdateChannelRequest;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

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

        return response()->json($channels);
    }

    public function show()
    {
        return inertia('Dashboard/AddingChannel');
    }

    public function store(StoreChannelRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $path = $file->store('public/images');
            $url = Storage::url($path);
            $validated['avatar'] = $url;
        }

        $validated['user_id'] = auth()->id();

        unset($validated['terms']);
        Channel::create($validated);

        return response()->json('success');
    }

    public function update(UpdateChannelRequest $request, Channel $channel)
    {
        $validated = $request->validated();

        if($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $path = $file->store('public/images');
            $url = Storage::url($path);
            $validated['avatar'] = $url;
        }
        unset($validated['terms']);
        $channel->update($validated);

        return response()->json($channel);
    }

    public function edit(Channel $channel)
    {

        $channelAvatar = asset($channel->avatar);

        return inertia('Dashboard/EditChannel', [
            'channelId' => $channel->id,
            'channel' => $channel,
            'channelAvatar' => $channelAvatar,
        ]);
    }
}
