<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Jobs\CheckPayoutConfirmationJob;
use App\Models\Setting;
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
        $min_amount = Setting::where('key', 'replenishment_min_amount')->first()->value;

        $request->validate([
            'amount' => ['required', 'numeric', 'min:'.$min_amount],
        ]);

        return $this->yooKassaService->createPayment($request);
    }

    public function createPayout(Request $request)
    {
        $request->validate([
            'cardNumbers' => 'required',
            'amount' => 'required'
        ]);

        $user = auth()->user();
        $pendingPayouts = Transaction::where('user_id', $user->id)
            ->whereIn('status', ['created', 'under_review'])
            ->where('appointment', 'Вывод')
            ->sum('amount');

        if ($user->balance < ($pendingPayouts + $request->amount)) {
            return response()->json(["message" => "Недостаточный баланс для этой операции"], 400);
        }

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

        CheckPayoutConfirmationJob::dispatch($transactionId)->delay(now()->addMinutes(10));
    }

    public function confirmPayout($token)
    {

        $transactionId = decrypt($token);
        $transaction = Transaction::where('transaction_id', $transactionId)->firstOrFail();

        $transaction->update(['status' => 'under_review']);

        $user = $transaction->user;
        $user->balance -= $transaction->amount;
        $user->save();

        $transaction->user->notify(new PayoutCreatedNotification($transactionId));

        return redirect()->route('withdraw');
    }
}
