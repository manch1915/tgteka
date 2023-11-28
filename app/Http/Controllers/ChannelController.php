<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTransaction;
use App\Services\ChannelAvatarService;
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
        return inertia('Dashboard/CatalogChannels');
    }

    public function channelsGet(Request $request, ChannelAvatarService $avatarService)
    {
        $channels = $this->fetchChannels($request, $avatarService);

        return response()->json($channels);
    }

    public function favorite(Request $request)
    {
        return response()->json($this->toggleFavorite($request), ResponseAlias::HTTP_OK);
    }

    public function show(Channel $channel, ChannelAvatarService $avatarService)
    {
        return inertia('Dashboard/CatalogChannelShow', ['channel' => $this->getChannelWithAvatar($channel, $avatarService)]);
    }

    public function orderPosts(Request $request)
    {
        return response()->json($this->orderService->createOrder($request));
    }

    protected function fetchChannels(Request $request, ChannelAvatarService $avatarService)
    {
        $search = $request->input('search');

        $channels = Channel::where('status', 'accepted')
            ->when($search, function ($query, $search) {
                return $query->where('channel_name', 'like', '%' . $search . '%');
            })->orderBy('created_at', 'desc')->paginate(10);

        $channels->each(function ($channel) use ($avatarService){
            $channel->avatar = $avatarService->getAvatarUrl($channel);
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

    protected function getChannelWithAvatar(Channel $channel, ChannelAvatarService $avatarService)
    {
        $channel->avatar = $avatarService->getAvatarUrl($channel);
        return $channel;
    }
}
