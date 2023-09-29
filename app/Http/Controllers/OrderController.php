<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        $orders = Order::all();

        return response()->json($orders);
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $order = Order::create($request->validated());

        return response()->json($order, 201);
    }

    public function show(Order $order): JsonResponse
    {
        return response()->json($order);
    }

    public function update(StoreOrderRequest $request, Order $order): JsonResponse
    {
        $order->update($request->validated());

        return response()->json($order);
    }

    public function destroy(Order $order): JsonResponse
    {
        $order->delete();

        return response()->json(null, 204);
    }
}
