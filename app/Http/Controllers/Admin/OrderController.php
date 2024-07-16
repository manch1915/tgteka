<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
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

    public function update(Request $request, Order $order)
    {
    }

    public function destroy(Order $order)
    {
    }
}