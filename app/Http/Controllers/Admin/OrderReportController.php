<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderReportResource;
use App\Models\OrderReport;
use Illuminate\Http\Request;

class OrderReportController extends Controller
{
    public function index(Request $request)
    {
        $query = OrderReport::with('order');

        $pageSize = $request->input('pageSize', 15);

        $searchParams = $request->all();

        $searchableFields = [
            'id',
            'order_id',
            'message',
            'status',
        ];

        foreach ($searchableFields as $field) {
            if (isset($searchParams[$field])) {
                $query->where($field, 'LIKE', '%' . $searchParams[$field] . '%');
            }
        }

        $reports = $query->paginate($pageSize);

        return response()->json($reports);
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show(OrderReport $orderReport)
    {
        $report = $orderReport->with('order', 'order.channel', 'order.format', 'order.user', 'order.pattern')->get();

        // Transform the model using the resource
        $orderReportResources = OrderReportResource::collection($report);

        return inertia('Admin/ReportsShow', ['report' => $orderReportResources]);
    }

    public function edit(OrderReport $orderReport)
    {
    }

    public function update(Request $request, OrderReport $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:accepted,declined',
        ]);

        $report->update($validated);

        if ($validated['status'] == 'accepted') {
            $report->order->status = 'declined';
        }

        return response()->json($report->order);
    }

    public function destroy(OrderReport $orderReport)
    {
    }
}
