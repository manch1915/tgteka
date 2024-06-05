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
        $query = Transaction::with('user')->where('appointment', 'Вывод');

        $searchParams = $request->all();

        $pageSize = $request->input('pageSize', 15);

        $searchableFields = [
            'id',
            'transaction_id',
            'username',
            'service',
            'details',
            'amount',
            'status',
        ];

        foreach ($searchableFields as $field) {
            if (isset($searchParams[$field])) {
                $query->where($field, 'LIKE', '%' . $searchParams[$field] . '%');
            }
        }
        logger($query->toSql() . json_encode($query->getBindings()));
        $transactions = $query->paginate($pageSize);

        return response()->json($transactions);
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
