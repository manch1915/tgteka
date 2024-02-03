<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Notifications\PayoutConfirmNotification;
use App\Notifications\PayoutCreatedNotification;
use App\Services\YooKassaService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FinanceController extends Controller
{
    private YooKassaService $yooKassaService;

    public function __construct(YooKassaService $yooKassaService)
    {
        $this->yooKassaService = $yooKassaService;
    }

    public function createYooKassaPayment(Request $request)
    {
        return $this->yooKassaService->createPayment($request);
    }

    public function createPayout(Request $request)
    {
        $request->validate([
            'cardNumbers' => 'required',
            'amount' => 'required'
        ]);

        do {
            $transactionId = 'TXN-' . Str::upper(Str::random(10));
        } while (Transaction::where('transaction_id', $transactionId)->exists());

        Transaction::create([
            'user_id' => auth()->id(),
            'status' => 'created',
            'service' => 'Банковская карта',
            'transaction_id' => $transactionId,
            'amount' => $request->amount,
            'appointment' => 'Вывод',
            'details' => $request->cardNumbers
        ]);

        $encryptedTransactionId = encrypt($transactionId);


        $confirmLink = route('confirm-payout', $encryptedTransactionId);

        auth()->user()->notify(new PayoutConfirmNotification($confirmLink, $request->amount ));
    }

    public function confirmPayout($token)
    {

        $transactionId = decrypt($token);
        $transaction = Transaction::where('transaction_id', $transactionId)->firstOrFail();

        $transaction->update(['status' => 'under_review']);

        $transaction->user->notify(new PayoutCreatedNotification($transactionId));
        // Redirect to a confirmation success page or to home page
        return redirect()->route('withdraw');
    }
}
