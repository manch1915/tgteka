<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Services\AvatarService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ChannelController extends Controller
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService) {
        $this->orderService = $orderService;
    }

    public function index()
    {
        return inertia('Dashboard/CatalogChannels', ['count' => Channel::where('status', '=', 'accepted')->count()]);
    }

    public function channelsGet(Request $request, AvatarService $avatarService)
    {
        $channels = $this->fetchChannels($request, $avatarService);

        return response()->json($channels);
    }

    public function favorite(Request $request)
    {
        return response()->json($this->toggleFavorite($request), ResponseAlias::HTTP_OK);
    }

    public function show(Channel $channel, AvatarService $avatarService)
    {
        return inertia('Dashboard/CatalogChannelShow', ['channel' => $this->getChannelWithAvatar($channel, $avatarService)]);
    }

    public function orderPosts(Request $request)
    {
        $validated = $request->validate([
            'channels' => 'required',
            'pattern_id' => 'required',
            'description' => 'required',
        ]);
        $response = $this->orderService->createOrder($request);

        if (is_string($response)) {
            return response()->json(['error' => $response], 400);
        }

        return response()->json(['message' => 'Order placed successfully', 'totalSum' => $response], 200);
    }


    protected function fetchChannels(Request $request, AvatarService $avatarService)
    {
        $search = $request->input('search');

        $channels = Channel::where('status', 'accepted')
            ->when($search, function ($query, $search) {
                return $query->where('channel_name', 'like', '%' . $search . '%');
            })->orderBy('created_at', 'desc')->paginate(10);

        $channels->each(function ($channel) use ($avatarService){
            $channel->avatar = $avatarService->getAvatarUrlOfChannel($channel);
            return $channel;
        });

        return $channels;
    }

    protected function toggleFavorite(Request $request)
    {
        $channel_id = $request->input('channel_id');
        $channel = Channel::find($channel_id);

        if (!$channel) {
            return [
                'status' => 'error',
                'message' => 'No channel found with the given ID.'
            ];
        }

        $user = auth()->user();

        if ($user->hasFavorited($channel)) {
            $user->unfavorite($channel);
            $message = 'Channel successfully removed from favorites.';
        } else {
            $user->favorite($channel);
            $message = 'Channel successfully added to favorites.';
        }

        return [
            'status' => 'success',
            'message' => $message,
        ];
    }

    protected function getChannelWithAvatar(Channel $channel, AvatarService $avatarService)
    {
        $channel->avatar = $avatarService->getAvatarUrlOfChannel($channel);
        return $channel;
    }
}
