<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PayoutResource;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayoutController extends Controller
{
    public function index(Request $request)
    {
        if ($request->status) {
            $transactions = Transaction::where('status', $request->status)->paginate(10);
        } else {
            $transactions = Transaction::where('status', 'under_review')->paginate(10);
        }
        return PayoutResource::collection($transactions);
    }

    public function store(Request $request)
    {
    }

    public function show(Transaction $transaction)
    {

    }

    public function update(Request $request, Transaction $payout)
    {
        $payout->status = $request->status;
        $payout->save();
        return response()->json(['message' => 'Transaction updated successfully'], 200);
    }

    public function destroy(Transaction $transaction)
    {
    }
}
