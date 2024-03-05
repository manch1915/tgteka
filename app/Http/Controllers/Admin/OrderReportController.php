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
        if (!$request->status) {
            return response()->json('error',400);
        }

        $reports = OrderReport::where('status', $request->status)->paginate(10);

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

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:accepted,declined',
        ]);
        $orderReport = OrderReport::findOrFail($id);
        
        $orderReport->update($validated);

        return response()->json($orderReport);
    }

    public function destroy(OrderReport $orderReport)
    {
    }
}
