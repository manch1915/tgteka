<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Http\Requests\ReviewRequest;
use App\Models\Order;
use App\Models\OrderReport;
use App\Models\Review;
use App\Notifications\ChannelReviewNotification;
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

    public function get(Request $request, $page = 1, $perPage = 10)
    {
        $orders = auth()->user()->orders()
            ->with(['format', 'channel.topic', 'pattern'])
            ->when($request->input('status'), fn($query, $status) => $query->where('status', $status))
            ->when($request->has('minPrice'), fn($query, $minPrice) => $query->where('price', '>=', intval($minPrice)))
            ->when($request->has('maxPrice'), fn($query, $maxPrice) => $query->where('price', '<=', intval($maxPrice)))
            ->when($request->has(['startDate', 'endDate']), fn($query) => $query->whereBetween('created_at', [
                Carbon::parse($request->input('startDate')),
                Carbon::parse($request->input('endDate'))->endOfDay()
            ]))
            ->orderByDesc('created_at')
            ->paginate($perPage, ['*'], 'page', $page);

        $additionalDaysMapping = ['1/24' => 1, '2/48' => 2, '3/72' => 3];

        $orders->getCollection()->transform(function ($order) use ($additionalDaysMapping) {
            $order->status = trans('messages.' . $order->status);

            $additionalDays = $additionalDaysMapping[$order->format->name] ?? 1;
            $order->post_date_end = Carbon::parse($order->post_date)->addDays($additionalDays)->format('Y-m-d H:i:s');

            $order->channel->channelAvatar = $this->avatarService->getAvatarUrlOfChannel($order->channel);
            $order->orderPattern = $order->pattern;
            $order->orderPattern->patternMedia = $this->avatarService->getAvatarUrlOfPattern($order->pattern);

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

    public function sendReview(ReviewRequest $request)
    {
        $validated = $request->validated();

        $order = Order::findOrFail($validated['order_id']);
        $channelId = $order->channel->id;

        $hasReviewed = Review::where([
            ['channel_id', '=', $channelId],
            ['user_id', '=', auth()->id()]
        ])->exists();

        if ($hasReviewed) {
            return response(['error' => 'Вы уже оставляли отзыв для этого канала'], 403);
        }

        Review::create([
            'channel_id' => $channelId,
            'rating' => $validated['rating'],
            'review_text' => $validated['review_text'],
            'user_id' => auth()->id()
        ]);

        $order->channel->user->notify(new ChannelReviewNotification($validated['review_text'], $validated['rating'], $order->channel->channel_name));
    }
}
