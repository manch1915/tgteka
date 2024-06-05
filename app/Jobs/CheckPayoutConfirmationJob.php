<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Notifications\PayoutFailedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckPayoutConfirmationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $transactionId;

    public function __construct($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    public function handle(): void
    {
        $transaction = Transaction::where('transaction_id', $this->transactionId)->first();

        if ($transaction && $transaction->status == 'created') {
            // Update transaction status to failed
            $transaction->update(['status' => 'failed']);

            // Notify the user
            $user = $transaction->user;
            $user->notify(new PayoutFailedNotification($this->transactionId));
        }
    }
}
