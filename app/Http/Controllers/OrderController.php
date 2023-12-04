<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Pattern;
use App\Notifications\PatternByBotNotification;
use App\Services\AvatarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $orders = auth()->user()->orders->load('items.format', 'items.channel.topic', 'pattern');

        $allItems = collect();


        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $item->orderPattern = $order->pattern;

                $item->orderPattern->patternMedia = $this->avatarService->getAvatarUrlOfPattern($order->pattern);
                $item->channel->channelAvatar = $this->avatarService->getAvatarUrlOfChannel($item->channel);
                $allItems->push($item);
            }
        }

        $offset = ($page * $perPage) - $perPage;

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $allItems->slice($offset, $perPage)->values(),
            $allItems->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]);
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
