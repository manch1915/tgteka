<?php

namespace App\Jobs;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use YooKassa\Client;
use YooKassa\Common\Exceptions\ApiException;
use YooKassa\Common\Exceptions\BadApiRequestException;
use YooKassa\Common\Exceptions\ExtensionNotFoundException;
use YooKassa\Common\Exceptions\ForbiddenException;
use YooKassa\Common\Exceptions\InternalServerError;
use YooKassa\Common\Exceptions\NotFoundException;
use YooKassa\Common\Exceptions\ResponseProcessingException;
use YooKassa\Common\Exceptions\TooManyRequestsException;
use YooKassa\Common\Exceptions\UnauthorizedException;
use YooKassa\Model\Payment\PaymentStatus;

class CheckPaymentStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Transaction $transaction;
    protected Client $client;

    public int $tries = 5;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->client = new Client;
        $this->client->setAuth(config('services.yookassa.client_id'), config('services.yookassa.client_secret'));
    }

    /**
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws ApiException
     * @throws ExtensionNotFoundException
     * @throws BadApiRequestException
     * @throws InternalServerError
     * @throws ForbiddenException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function handle(): void
    {
        $response = $this->client->getPaymentInfo($this->transaction->transaction_id);

        if ($response && $response->getStatus() === PaymentStatus::SUCCEEDED) {
            // Update Transaction Status
            $this->transaction->update(['status' => 'succeeded']);

            // Update balance of the User associated with Transaction
            $this->transaction->user()->increment('balance', $this->transaction->amount);
        } else {
            if ($this->attempts() < $this->tries) {
                $this->release(120);
            }else {
                $this->transaction->update(['status' => 'failed']);
            }
        }
    }

}
