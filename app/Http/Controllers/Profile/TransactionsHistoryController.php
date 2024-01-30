<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TransactionsHistoryController extends Controller
{
    public function index()
    {
        return inertia('Dashboard/Profile/TransactionsHistory');
    }
    public function getTransactions(Request $request)
    {
        $appointment = $request->input('appointment');
        $status = $request->input('status');

        $transactions = auth()->user()->transactions();

        if ($appointment) {
            $transactions->where('appointment', $appointment);
        }

        if ($status) {
            $transactions->where('status', $status);
        }

        $transactions->when($request->has(['startDate', 'endDate']), fn($query) => $query->whereBetween('created_at', [
            Carbon::parse($request->input('startDate')),
            Carbon::parse($request->input('endDate'))->endOfDay()
        ]));

        $transactions = $transactions->orderBy('created_at', 'desc')->paginate(10);

        return TransactionResource::collection($transactions);
    }
}
