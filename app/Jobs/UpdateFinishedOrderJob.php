<?php

namespace App\Jobs;

use App\Models\Order;
use App\Notifications\OrderCompletedForAdminNotification;
use App\Notifications\OrderCompletedForUserNotification;
use App\Services\BalanceService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateFinishedOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Order $order;

    protected BalanceService $balanceService;

    const RELEASE_DURATION = 3600; // 1 hour

    public int $tries = 8;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order, BalanceService $balanceService)
    {
        $this->order = $order;
        $this->balanceService = $balanceService;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->shouldMarkAsDeclined()) {
            $this->markOrderAsDeclined();

            return;
        }

        if ($this->canMarkAsFinished()) {
            $this->processOrderCompletion();
        } else {
            $this->release(60 * 60); // Retry in an hour
        }
    }

    /**
     * Check if the order should be marked as declined.
     */
    protected function shouldMarkAsDeclined(): bool
    {
        return $this->order->post_date_end < Carbon::now() && $this->order->status === 'check';
    }

    /**
     * Mark the order as declined.
     */
    protected function markOrderAsDeclined(): void
    {
        $this->order->markAsDeclined();
    }

    /**
     * Check if the order can be marked as finished.
     */
    protected function canMarkAsFinished(): bool
    {
        return ! $this->order->orderReports()->where('status', 'pending')->exists()
            && $this->order->status === 'checked'
            && $this->order->post_date_end < Carbon::now();
    }

    /**
     * Process the completion of the order.
     */
    protected function processOrderCompletion(): void
    {
        $channelAdmin = $this->order->channel->user;

        // Add balance to the channel admin
        $this->balanceService->addToUserBalance($channelAdmin, $this->order->price);

        // Send notifications to the channel admin and the user
        $channelAdmin->notify(new OrderCompletedForAdminNotification($this->order->price, $this->order->channel->channel_name));
        $this->order->user->notify(new OrderCompletedForUserNotification($this->order->channel->channel_name, $this->order));

        // Mark the order as finished
        $this->order->markAsFinished();
    }

    /**
     * Handle job failure (when the job is about to fail).
     */
    public function failed(): void
    {
        // Mark the order as declined when the job fails after all attempts
        $this->markOrderAsDeclined();
    }
}
