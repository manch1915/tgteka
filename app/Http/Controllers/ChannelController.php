<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ChannelController extends Controller
{
    public function index()
    {
        return inertia('Dashboard/CatalogChannels');
    }

    public function channelsGet(Request $request)
    {
        $search = $request->input('search');

        $channels = Channel::where('status', 'accepted')
            ->when($search, function ($query, $search) {
                return $query->where('channel_name', 'like', '%' . $search . '%');
            })->orderBy('created_at', 'desc')->paginate(10);

        $channels->each(function ($channel) {
            $channel->avatar_url = $channel->getMedia('avatars')->last()->getUrl();
            return $channel;
        });

        return response()->json($channels);
    }

    public function favorite(Request $request)
    {
        $channel_id = $request->input('channel_id');

        $channel = Channel::find($channel_id);

        if (!$channel) {
            return response()->json([
                'status' => 'error',
                'message' => 'No channel found with the given ID.'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }


        $user = Auth::user();

        if ($user->hasFavorited($channel)) {
            $user->unfavorite($channel);
            $message = 'Channel successfully removed from favorites.';
        } else {
            $user->favorite($channel);
            $message = 'Channel successfully added to favorites.';
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
        ], ResponseAlias::HTTP_OK);
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show(Channel $channel)
    {
        return inertia('Dashboard/CatalogChannelShow', ['channel' => $channel]);
    }

    public function edit(Channel $channel)
    {
    }

    public function update(Request $request, Channel $channel)
    {
    }

    public function destroy(Channel $channel)
    {
    }
}
