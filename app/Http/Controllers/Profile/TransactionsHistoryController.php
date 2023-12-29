<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use Illuminate\Http\Request;

class TransactionsHistoryController extends Controller
{
    public function index()
    {
        return inertia('Dashboard/Profile/TransactionsHistory');
    }
    public function getTransactions()
    {
        $transactions = auth()->user()->transactions();
        $transactions = $transactions->orderBy('created_at', 'desc');
        $transactions = $transactions->paginate(10);

        return TransactionResource::collection($transactions);
    }
}
