<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\SuggestedDate;
use Exception;

class SuggestedDateController extends Controller
{
    /**
     * @throws \DateMalformedStringException
     * @throws Exception
     */
    public function accept($id, $suggestedDate)
    {
        $order = Order::findOrFail($id);

        if (auth()->user()->id !== $order->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $formatDay = $this->getFormatDetails($order);

        // Calculate post_date_end
        $postDateEnd = (new \DateTime($suggestedDate))->modify('+'.$formatDay.' day');

        // Update post_date and post_date_end
        $order->update([
            'post_date' => $suggestedDate,
            'post_date_end' => $postDateEnd,
            'status' => 'accepted',
        ]);

        return response()->json(['status' => 'accepted']);
    }

    /**
     * @throws Exception
     */
    private function getFormatDetails(Order $order): int
    {
        $formatParts = explode('/', $order->format->name);

        return $formatParts[0];
    }

    public function decline($id)
    {
        $suggestedDate = SuggestedDate::findOrFail($id);

        if (auth()->user()->id !== $suggestedDate->order->user->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $suggestedDate->order->update(['status' => 'declined']);

        return response()->json(['status' => 'declined']);
    }
}
