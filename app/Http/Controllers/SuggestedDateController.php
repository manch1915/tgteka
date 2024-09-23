<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\SuggestedDate;
use Illuminate\Http\JsonResponse;
use Exception;

class SuggestedDateController extends Controller
{
    /**
     * Handle the acceptance of a suggested date using the Order ID.
     *
     * @throws Exception
     */
    public function accept($orderId, $suggestedDate): JsonResponse
    {
        $order = Order::findOrFail($orderId);

        // Ensure only the owner of the order can accept
        if (auth()->user()->id !== $order->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Check if the suggested date has already been handled
        if ($order->suggestedDate->handled) {
            return response()->json(['error' => 'Это предложение уже было обработано ранее.'], 400);
        }

        // Calculate post_date_end
        $formatDay = explode('/', $order->format->name)[0]; // Extract number of days from format
        $postDateEnd = (new \DateTime($suggestedDate))->modify("+$formatDay day");

        // Update post_date, post_date_end, and status
        $order->update([
            'post_date' => $suggestedDate,
            'post_date_end' => $postDateEnd,
            'status' => 'accepted',
        ]);

        // Mark the suggested date as handled
        $order->suggestedDate()->update(['handled' => true]);

        return response()->json(['status' => 'accepted']);
    }

    /**
     * Handle the decline of a suggested date using the SuggestedDate ID.
     *
     * @throws Exception
     */
    public function decline($suggestedDateId): JsonResponse
    {
        $suggestedDate = SuggestedDate::findOrFail($suggestedDateId);
        $order = $suggestedDate->order;

        // Ensure only the owner of the order can decline
        if (auth()->user()->id !== $order->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Check if the suggested date has already been handled
        if ($suggestedDate->handled) {
            return response()->json(['error' => 'Это предложение уже было обработано ранее.'], 400);
        }

        // Update order status and mark the suggested date as handled
        $order->update(['status' => 'declined']);
        $suggestedDate->update(['handled' => true]);

        return response()->json(['status' => 'declined']);
    }
}
