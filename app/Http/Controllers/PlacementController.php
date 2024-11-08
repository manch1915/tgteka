<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Http\Requests\ReviewRequest;
use App\Models\ChannelStatistic;
use App\Models\Order;
use App\Models\OrderReport;
use App\Models\Review;
use App\Notifications\ChannelReviewNotification;
use App\Notifications\OrderReportNotification;
use App\Services\AvatarService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

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

    public function get(Request $request)
    {
        $userOrders = auth()->user()->orders();

        $orders = (clone $userOrders)
            ->with(['format', 'channel.topic', 'pattern'])
            ->when($request->input('status'), fn($query, $status) => $query->where('status', $status))
            ->when($request->input('minPrice'), fn($query, $minPrice) => $query->where('price', '>=', intval($minPrice)))
            ->when($request->input('maxPrice'), fn($query, $maxPrice) => $query->where('price', '<=', intval($maxPrice)))
            ->when($request->has(['startDate', 'endDate']), fn($query) => $query->whereBetween('created_at', [
                Carbon::parse($request->input('startDate')),
                Carbon::parse($request->input('endDate'))->endOfDay()
            ]))
            ->orderByDesc('created_at')
            ->paginate(10, ['*']);

        $maxPrice = $userOrders->max('price');
        $hasAnyOrder = $userOrders->exists();

        $additionalDaysMapping = ['1/24' => 1, '2/48' => 2, '3/72' => 3];

        $orders->getCollection()->transform(function ($order) use ($additionalDaysMapping) {

            $additionalDays = $additionalDaysMapping[$order->format->name] ?? 1;
            $order->post_date_end = Carbon::parse($order->post_date)->addDays($additionalDays)->format('Y-m-d H:i:s');

            $channelStats = ChannelStatistic::where('channel_id', $order->channel->id)->first(['stats']);

            if ($channelStats) {
                $order->channel->statistics = json_decode($channelStats->stats, true);
                $order->channel->participants_count = $order->channel->statistics['participants_count'] ?? null;
            }

            $order->channel->channelAvatar = $this->avatarService->getAvatarUrlOfChannel($order->channel);
            $order->orderPattern = $order->pattern;
            $order->orderPattern->patternMedia = $this->avatarService->getAvatarUrlOfPattern($order->pattern);

            return $order;
        });

       return response()->json(['orders' =>$orders, 'max_price' => $maxPrice, 'hasAnyOrder' => $hasAnyOrder]);
    }

    public function sendReport(ReportRequest $request)
    {
        try {
            $validated = $request->validated();

            $order = Order::findOrFail($validated['order_id']);

            $reportExists = OrderReport::where('order_id', $validated['order_id'])->exists();

            if($reportExists) {
                return response(['message' => 'Вы уже отправили жалобу на этот заказ. Пожалуйста, подождите, пока администраторы проверят вашу жалобу.'], 400);
            }

            OrderReport::create([
                'order_id' => $validated['order_id'],
                'message' => $validated['report_message']
            ]);

            $order->channel->user->notify(new OrderReportNotification($validated['report_message'], $order));
            return response(['message' => 'Мы получили вашу жалобу.'], 200);
        } catch (\Exception $e) {
            Log::error('Unable to send report: ', ['exception' => $e]);
            return response(['error' => 'Не удается отправить отчет из-за ошибки. Пожалуйста, попробуйте еще раз или обратитесь в службу поддержки, если проблема не устранена.'], 500);
        }
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

        $review = Review::create([
            'channel_id' => $channelId,
            'rating' => $validated['rating'],
            'review_text' => $validated['review_text'],
            'user_id' => auth()->id()
        ]);

        $order->channel->user->notify(new ChannelReviewNotification($validated['review_text'], $validated['rating'], $order->channel->channel_name));

        return $review;
    }

    public function acceptOrder(Request $request, Order $order)
    {
        try {
            if ($order->status === 'check') {
                $order->status = 'checked';
                $order->save();
            } else {
                return response()->json(['error' => 'Статус заказа должен быть проверен, чтобы обновить его до проверено'], 400);
            }
            return $order;
        } catch (\Exception $e) {
            Log::error('Error while accepting order: ', ['exception' => $e]);
            return response(['error' => 'При принятии заказа произошла ошибка. Пожалуйста, попробуйте снова.'], 500);
        }
    }
}
