<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Models\Order;
use App\Models\OrderReport;
use App\Notifications\OrderReportNotification;
use App\Services\AvatarService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PlacementController extends Controller
{
    protected AvatarService $avatarService;

    public function __construct(AvatarService $avatarService)
    {
        $this->avatarService = $avatarService;
    }
    public function index()
    {
        return inertia('Dashboard/Placements');
    }
    public function get($page = 1, $perPage = 10)
    {
        $orders = auth()->user()->orders()->with('format', 'channel.topic', 'pattern')
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

            $order->orderPattern->patternMedia = $this->avatarService->getAvatarUrlOfPattern($order->pattern);
            $order->channel->channelAvatar = $this->avatarService->getAvatarUrlOfChannel($order->channel);

            return $order;
        });

        return $orders;
    }

    public function sendReport(ReportRequest $request)
    {
        $validated = $request->validated();

        OrderReport::create([
            'order_id' => $validated['order_id'],
            'message' => $validated['report_message']
        ]);

        $order = Order::find($validated['order_id']);
        $order->channel->user->notify(new OrderReportNotification($validated['report_message']));

    }
}
