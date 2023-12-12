<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Pattern;
use App\Models\SuggestedDate;
use App\Notifications\OrderAcceptedNotification;
use App\Notifications\OrderDeclinedNotification;
use App\Notifications\OrderSuggestedDateNotification;
use App\Notifications\PatternByBotNotification;
use App\Services\AvatarService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
        $orders = auth()->user()->orders->load('format', 'channel.topic', 'pattern');

        $allItems = collect();

        foreach ($orders as $order) {
            $order->orderPattern = $order->pattern;

            $order->orderPattern->patternMedia = $this->avatarService->getAvatarUrlOfPattern($order->pattern);
            $order->channel->channelAvatar = $this->avatarService->getAvatarUrlOfChannel($order->channel);
            $allItems->push($order);
        }

        $offset = ($page * $perPage) - $perPage;

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $allItems->slice($offset, $perPage)->values(),
            $allItems->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]);
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

        $date = $request->has('suggested_date')
            ? Carbon::createFromTimestampMs($request->suggested_date)->format('Y-m-d H:i:s')
            : null;

        if ($date) {
            $suggestedDate = SuggestedDate::updateOrCreate(['order_id' => $orderItem->id], [
                'suggested_post_date' => $date
            ]);
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

    public function sendPatternByBot(Request $request, AvatarService $avatarService)
    {
        $pattern = $request->input('pattern');
        $pattern = Pattern::findOrFail($pattern['id']);
        $patternByBot = new PatternByBotNotification($pattern, $avatarService);
        auth()->user()->notify($patternByBot);
        return response()->json();
    }
}
