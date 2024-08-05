<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderUpdateRequest;
use App\Models\Order;
use App\Notifications\OrderDeclinedByModeratorNotification;
use App\Services\BalanceService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $pageSize = $request->input('pageSize', 15);
        $orders = Order::with(['user', 'channel', 'format', 'orderReports'])->paginate($pageSize);

        return response()->json($orders);
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show(Order $order)
    {
    }

    public function edit(Order $order)
    {
    }

    public function update(OrderUpdateRequest $request, Order $order)
    {
        // Validate the request
        $validatedData = $request->validated();

        if ($order->status === $validatedData['status']) {
            return response()->json(['message' => 'Order status is already ' . $validatedData['status']], 400);
        }

        if ($validatedData['status'] == 'declined' && !$order->refund_issued) {
            $balanceService = new BalanceService();
            $balanceService->refundUser($order);

            // Mark refund as issued
            $order->refund_issued = true;
            $order->save();
            $order->user->notify(new OrderDeclinedByModeratorNotification());
        }

        $order->update([
            'status' => $validatedData['status'],
        ]);

        // Return a success response
        return response()->json(['message' => 'Order status updated successfully']);
    }

    public function destroy(Order $order)
    {
    }
}
