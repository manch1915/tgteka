<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\ChannelStatistic;
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
        if (!session()->has("viewed_channel.{$channel->id}")) {
            $channel->increment('views_count');
            session()->put("viewed_channel.{$channel->id}", true);
        }
        return inertia('Dashboard/CatalogChannelShow', ['channel' => $this->getChannelWithAvatarAndTopic($channel, $avatarService), 'favorites_count' => $channel->favoritesCount] );
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

    public function fetchChannelStatistics($channelId)
    {
        $statistics = ChannelStatistic::where('channel_id', $channelId)->first(['stats']);

        $statisticsArray = json_decode($statistics->stats, true);

        return response()->json($statisticsArray);
    }

    public function fetchChannelStatisticsAll($channelId)
    {
        $channel = ChannelStatistic::where('channel_id', $channelId)->first(['stats', 'subscribers', 'avg_posts_reach', 'er']);

        $decodedData = [
            'stats' => json_decode($channel->stats, true)['response'],
            'subscribers' => json_decode($channel->subscribers, true)['response'],
            'avg_posts_reach' => json_decode($channel->avg_posts_reach, true)['response'],
            'er' => json_decode($channel->er, true)['response'],
        ];

        return response()->json($decodedData);
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

    protected function getChannelWithAvatarAndTopic(Channel $channel, AvatarService $avatarService)
    {
        $channel->avatar = $avatarService->getAvatarUrlOfChannel($channel);
        $channel->load('topic');
        return $channel;
    }
}
