<?php

namespace App\Jobs;

use App\Notifications\OrderCompletedForAdminNotification;
use App\Notifications\OrderCompletedForUserNotification;
use App\Services\BalanceService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class UpdateFinishedOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Order $order;
    protected BalanceService $balanceService;

    const RELEASE_DURATION = 3600;
    public $tries = 8;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order, BalanceService $balanceService)
    {
        $this->order = $order;
        $this->balanceService = $balanceService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        if ($this->order->orderReports()->where('status', 'pending')->exists()) {
            // Release the job and retry after 4 hours
            $this->release(60 * 60 * 4);
            return;
        }
        // Second Condition: Check if the order is accepted and post date end is in the future
        if ($this->order->status === 'accepted' &&
            $this->order->post_date_end > Carbon::now()) {
            // Order's post date end is not yet reached
            $this->release(self::RELEASE_DURATION); // Try the job again after 1 hour
        }
        else {
            $channelAdmin = $this->order->channel->user;
            $this->balanceService->addToUserBalance($channelAdmin, $this->order->price);

            $channelAdmin->notify(new OrderCompletedForAdminNotification($this->order->price, $this->order->channel->channel_name));
            $this->order->user->notify(new OrderCompletedForUserNotification($this->order->channel->channel_name));

            $this->order->markAsFinished();
        }
    }
}
