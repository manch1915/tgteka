<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\SuggestedDate;

class SuggestedDateController extends Controller
{
    public function accept($id ,$suggestedDate)
    {
        $order = Order::findOrFail($id);

        if (auth()->user()->id !== $order->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $order->update(['post_date' => $suggestedDate]);

        return response()->json(['status' => 'accepted']);
    }

    public function decline($id)
    {
        $suggestedDate = SuggestedDate::findOrFail($id);

        if (auth()->user()->id !== $suggestedDate->order->user->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $suggestedDate->order->status = 'decline';
        $suggestedDate->order->save();

        return response()->json(['status' => 'declined']);
    }
}
