<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PayoutResource;
use App\Models\Transaction;
use App\Notifications\PayoutStatusUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayoutController extends Controller
{
    public function index(Request $request)
    {
        if ($request->status) {
            $transactions = Transaction::with('user')
                ->where('status', $request->status)
                ->where('appointment', 'Вывод')
                ->paginate(10);
        } else {
            $transactions = Transaction::with('user')
                ->where('status', 'under_review')
                ->where('appointment', 'Вывод')
                ->paginate(10);
        }
        return PayoutResource::collection($transactions);
    }

    public function countStatuses()
    {
        $statusCounts = Transaction::selectRaw('status, COUNT(*) as count')
            ->where('appointment', 'Вывод')
            ->groupBy('status')->get();

        return $statusCounts->keyBy('status')->toArray();
    }

    public function store(Request $request)
    {
    }

    public function show(Transaction $transaction)
    {

    }

    public function update(Request $request, Transaction $payout)
    {
        if($request->status === 'rejected') {
            $payout->user->balance += $payout->amount;
            $payout->user->save();
        }

        $payout->status = $request->status;
        $payout->save();

        $payout->user->notify(new PayoutStatusUpdatedNotification($payout->id,__('messages.' . $request->status),));

        return response()->json(['message' => 'Транзакция успешно обновлена'], 200);
    }

    public function destroy(Transaction $transaction)
    {
    }
}
