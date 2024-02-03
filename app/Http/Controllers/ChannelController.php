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
        $order = $request->input('order');

        $sort = $request->input('sort', 'desc');

        $channelsQuery = Channel::query();

        $channelsQuery->where('status', '=', 'accepted');

        $channelsQuery
            ->leftJoin('channel_statistics', 'channels.id', '=', 'channel_statistics.channel_id')
            ->whereNotNull('channel_statistics.stats');

        if ($order != null) {
            $channelsQuery->whereRaw("JSON_CONTAINS_PATH(channel_statistics.stats, 'one', '$." . $order . "')");
            $orderDirection = $sort === 'desc' ? 'desc' : 'asc';
            $channelsQuery->orderByRaw("CAST(JSON_EXTRACT(channel_statistics.stats, '$." . $order . "') as UNSIGNED) " . $orderDirection);
        }

        if ($search != null && $search != '') {
            $channelsQuery->where('channel_name', 'like', '%' . $search . '%');
        }

        foreach ($request->input() as $key => $value) {
            if ($key === 'peerType') {
                $channelsQuery->where('type', '=', $value);
            } elseif (str_contains($key, '_min')) {
                // Handle min value
                $field = str_replace('_min', '', $key);
                $channelsQuery->whereRaw("CAST(JSON_EXTRACT(channel_statistics.stats, '$." . $field . "') as UNSIGNED) >= ?", [$value]);
            } elseif (str_contains($key, '_max')) {
                // Handle max value
                $field = str_replace('_max', '', $key);
                $channelsQuery->whereRaw("CAST(JSON_EXTRACT(channel_statistics.stats, '$." . $field . "') as UNSIGNED) <= ?", [$value]);
            }
        }


        $channels = $channelsQuery->paginate(10);

        $channels->each(function ($channel) use ($avatarService, $request) {
            $channel->isFav = $request->user()->hasFavorited($channel);
            $channel->avatar = $avatarService->getAvatarUrlOfChannel($channel);
            $channel->statistics = $channel->stats ? json_decode($channel->stats, true) : [];
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
        $channelStatistic = ChannelStatistic::where('channel_id', $channelId)->first(['stats', 'subscribers', 'avg_posts_reach', 'er', 'channel_id']);
        $channel = Channel::find($channelStatistic->channel_id);

        $finishedOrdersQuery = $channel->orders()->where('status', 'finished');

        $finishedOrdersCount = $finishedOrdersQuery->count();
        $finishedOrdersPriceSum = $finishedOrdersQuery->sum('price');

        $decodedData = [
            'stats' => json_decode($channelStatistic->stats, true)['response'],
            'subscribers' => json_decode($channelStatistic->subscribers, true)['response'],
            'avg_posts_reach' => json_decode($channelStatistic->avg_posts_reach, true)['response'],
            'er' => json_decode($channelStatistic->er, true)['response'],
            'finished_orders_count' => $finishedOrdersCount,
            'finished_orders_price_sum' => $finishedOrdersPriceSum
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

    public function fetchChannelReviews($channelId)
    {
        $reviews = Channel::find($channelId)->reviews()
            ->with('user:id,email')
            ->get();

        return response()->json($reviews);
    }

    public function fetchChannelOrdersCount($channelId)
    {
        $ordersCount = Channel::find($channelId)->orders()->count();

        return response()->json($ordersCount);
    }

    protected function getChannelWithAvatarAndTopic(Channel $channel, AvatarService $avatarService)
    {
        $channel->avatar = $avatarService->getAvatarUrlOfChannel($channel);
        $channel->load('topic');
        return $channel;
    }
}
