<?php

namespace App\Http\Controllers;

use App\Http\Requests\ToCheckRequest;
use App\Jobs\CheckOrderStatusJob;
use App\Models\ChannelStatistic;
use App\Models\Order;
use App\Models\Pattern;
use App\Models\SuggestedDate;
use App\Notifications\OrderAcceptedNotification;
use App\Notifications\OrderDeclinedNotification;
use App\Notifications\OrderSuggestedDateNotification;
use App\Notifications\PatternByBotNotification;
use App\Notifications\OrderToCheckNotification;
use App\Services\AvatarService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected AvatarService $avatarService;

    public function __construct(AvatarService $avatarService)
    {
        $this->avatarService = $avatarService;
    }

    public function index()
    {
       return inertia('Dashboard/Orders');
    }

    public function get($page = 1, $perPage = 10)
    {
        $orders = auth()->user()->channelOrders()->with('format', 'channel.topic')
            ->orderByDesc('created_at')
            ->paginate($perPage, ['*'], 'page', $page);

        $orders->getCollection()->transform(function ($order) {
            $order->orderPattern = $order->pattern;

            $additionalDays = match($order->format->name) {
                '1/24' => 1,
                '2/48' => 2,
                '3/72' => 3,
                default => 1,
            };

            $post_date_end = Carbon::parse($order->post_date)->addDays($additionalDays);
            $order->post_date_end = $post_date_end->format('Y-m-d H:i:s');

            $channelStats = ChannelStatistic::where('channel_id', $order->channel->id)->first(['stats']);

            if ($channelStats) {
                $order->channel->statistics = json_decode($channelStats->stats, true);
                $order->channel->participants_count = $order->channel->statistics['participants_count'] ?? null;
            }

            $patternMedia = $order->pattern
                ->getMedia('images')
                ->map(function ($item) {
                    return [
                        'url' => $item->getFullUrl(),
                        'order' => $item->getCustomProperty('order'),
                        'thumbnail_path' => $item->getCustomProperty('thumbnail_path')
                    ];
                });


            $order->orderPattern->patternMedia = $patternMedia->sortBy('order')->values();
            $order->channel->channelAvatar = $this->avatarService->getAvatarUrlOfChannel($order->channel);
            return $order;
        });

        return $orders;
    }

    public function acceptOrder($orderItemId)
    {
        $orderItem = Order::find($orderItemId);
        if(!$orderItem) {
            return response()->json(['message' => 'Заказ не найден'], 404);
        }

        $orderItem->status = 'accepted';
        $orderItem->save();

        $orderItem->user->notify(new OrderAcceptedNotification($orderItem->channel->channel_name));

        return response()->json(['message' => 'Заказ успешно принят']);
    }

    public function declineOrder($orderItemId, Request $request)
    {
        $orderItem = Order::find($orderItemId);

        if(!$orderItem) {
            return response()->json(['message' => 'Заказ не найден'], 404);
        }

        if ($request->has('suggested_date')) {
        $date = Carbon::createFromTimestampMs($request->suggested_date)->format('Y-m-d H:i:s');

        $suggestedDate = SuggestedDate::updateOrCreate(
                ['order_id' => $orderItem->id],
                ['suggested_post_date' => $date]
            );
            $orderItem->user->notify(new OrderSuggestedDateNotification($date, $suggestedDate->id, $orderItem->id));
        } else {
            SuggestedDate::where('order_id', $orderItem->id)->first()?->delete();
            $orderItem->status = 'declined';
            $orderItem->decline_reason = $request->reason;
            $orderItem->save();
        }

        $orderItem->user->notify(new OrderDeclinedNotification($request->reason));
        return response()->json(['message' => 'Заказ успешно отклонен']);
    }

    public function toCheck(ToCheckRequest $request)
    {
        $validated = $request->validated();

        $order = Order::findOrFail($validated['orderId']);

        $order->status = 'check';
        $order->save();

        $order->user->notify(new OrderToCheckNotification($validated['post_link']));
        CheckOrderStatusJob::dispatch($order)->delay(now()->addHours(24));
        return response()->json($validated);
    }

    public function sendPatternByBot(Request $request, AvatarService $avatarService)
    {
        $pattern = $request->input('pattern');
        $pattern = Pattern::findOrFail($pattern['id']);
        $patternByBot = new PatternByBotNotification($pattern);
        auth()->user()->notify($patternByBot);
        return response()->json();
    }
}
