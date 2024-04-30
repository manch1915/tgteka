<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\ChannelStatistic;
use App\Services\AvatarService;
use App\Services\ChannelService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $request->validate([
            'channel_id' => 'required|exists:channels,id',
        ]);

        return response()->json($this->toggleFavorite($request), ResponseAlias::HTTP_OK);
    }

    public function show(Channel $channel, AvatarService $avatarService)
    {
        if (!session()->has("viewed_channel.{$channel->id}")) {
            $channel->increment('views_count');
            session()->put("viewed_channel.{$channel->id}", true);
        }
        return inertia('Dashboard/CatalogChannelShow', ['channel' => $this->getChannelWithAvatarAndTopic($channel, $avatarService), 'favorites_count' => $channel->favorites()->count()] );
    }

    /**
     * @throws \Exception
     */
    public function orderPosts(Request $request)
    {
        $request->validate([
            'channels' => 'required',
            'pattern_id' => 'required',
            'description' => 'required',
        ]);

        $response = $this->orderService->createOrder($request);

        if (is_string($response)) {
            return response()->json(['message' => $response], 400);
        }

        return response()->json(['message' => 'Order placed successfully', 'totalSum' => $response], 200);
    }

    protected function fetchChannels(Request $request)
    {
        $channelService = new ChannelService(new AvatarService());
        return $channelService->fetchChannels($request);
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

        if(null === $channelStatistic) {
            return response()->json(['error' => 'Статистика канала будет загружён только после одобрения канала администраторами'], 404);
        }

        $channel = Channel::find($channelStatistic->channel_id);

        $finishedOrdersQuery = $channel->orders()->where('status', 'finished');

        $finishedOrdersCount = $finishedOrdersQuery->count();
        $finishedOrdersPriceSum = $finishedOrdersQuery->sum('price');

        // Fetch all reviews for the channel
        $reviews = $channel->reviews()->get();

        // Calculate the average rating from reviews
        $totalRating = $reviews->sum('rating');
        $reviewCount = $reviews->count();
        $averageRating = $reviewCount > 0 ? $totalRating / $reviewCount : 0;

        $decodedData = [
            'stats' => json_decode($channelStatistic->stats, true),
            'subscribers' => json_decode($channelStatistic->subscribers, true),
            'avg_posts_reach' => json_decode($channelStatistic->avg_posts_reach, true),
            'er' => json_decode($channelStatistic->er, true),
            'finished_orders_count' => $finishedOrdersCount,
            'finished_orders_price_sum' => $finishedOrdersPriceSum,
            'average_review_rating' => $averageRating,
            'channel' => $channel
        ];

        return response()->json($decodedData);
    }

    protected function toggleFavorite(Request $request)
    {
        $channel_id = $request->input('channel_id');
        logger()->info($channel_id);
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



    public function fetchBest(Request $request, AvatarService $avatarService)
    {
        // Retrieve the topic from the request
        $topic = $request->input('topic');

        // Start building the query to select the top 6 channels based on the participants_count statistic
        $bestChannelsQuery = ChannelStatistic::select('channel_id')
            ->selectRaw('JSON_EXTRACT(stats, "$.participants_count") as participants_count')
            ->selectRaw('JSON_EXTRACT(stats, "$.adv_post_reach_24h") as adv_post_reach_24h')
            ->orderByRaw('CAST(JSON_EXTRACT(stats, "$.participants_count") AS UNSIGNED) DESC')
            ->limit(6);

        // If a topic is provided, filter channels by topic
        if ($topic) {
            $bestChannelsQuery->whereIn('channel_id', function ($query) use ($topic) {
                $query->select('id')
                    ->from('channels')
                    ->where('topic_id', $topic);
            });
        }

        // Execute the query to get the best channels
        $bestChannels = $bestChannelsQuery->get();

        // Extract the channel IDs from the result
        $channelIds = $bestChannels->pluck('channel_id');

        // Fetch the channel data for the selected IDs
        $channels = Channel::whereIn('id', $channelIds)->get();

        // Merge the channel data with the statistics
        $bestChannelsWithDetails = $bestChannels->map(function ($item) use ($avatarService, $channels) {
            $channel = $channels->where('id', $item->channel_id)->first();
            $channel->avatar = $avatarService->getAvatarUrlOfChannel($channel);
            $item->channel = $channel;
            return $item;
        });

        // Return the response with channels and their statistics
        return response()->json($bestChannelsWithDetails);
    }


    protected function getChannelWithAvatarAndTopic(Channel $channel, AvatarService $avatarService)
    {
        $channel->avatar = $avatarService->getAvatarUrlOfChannel($channel);
        $channel->load('topic');
        return $channel;
    }
}
