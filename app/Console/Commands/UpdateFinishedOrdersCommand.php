<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Notifications\OrderCompletedForAdminNotification;
use App\Notifications\OrderCompletedForUserNotification;
use App\Services\BalanceService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateFinishedOrdersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:update-finished';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the status of finished orders to "finish" or "declined".';

    protected BalanceService $balanceService;

    /**
     * Create a new command instance.
     */
    public function __construct(BalanceService $balanceService)
    {
        parent::__construct();
        $this->balanceService = $balanceService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $chunkSize = 200;

        Order::where('post_date_end', '<', Carbon::now())
            ->where('status', 'checked')
            ->whereDoesntHave('orderReports')
            ->orWhereHas('orderReports', function ($query) {
                $query->where('status', 'declined');
            })
            ->chunk($chunkSize, function ($orders) {
                foreach ($orders as $order) {
                    $this->processOrder($order);
                }
            });

        $this->info('Orders have been updated successfully!');
    }

    /**
     * Process each order to either mark it as finished or declined.
     */
    protected function processOrder(Order $order): void
    {
        if ($this->shouldMarkAsDeclined($order)) {
            $this->markOrderAsDeclined($order);
        } else {
            $this->processOrderCompletion($order);
        }
    }

    /**
     * Check if the order should be marked as declined.
     */
    protected function shouldMarkAsDeclined(Order $order): bool
    {
        return $order->post_date_end < Carbon::now() && $order->status === 'check';
    }

    /**
     * Mark the order as declined.
     */
    protected function markOrderAsDeclined(Order $order): void
    {
        $order->markAsDeclined();
    }

    /**
     * Process the completion of the order.
     */
    protected function processOrderCompletion(Order $order): void
    {
        $channelAdmin = $order->channel->user;

        // Add balance to the channel admin
        $this->balanceService->addToUserBalance($channelAdmin, $order->price);

        // Send notifications to the channel admin and the user
        $channelAdmin->notify(new OrderCompletedForAdminNotification($order->price, $order->channel->channel_name));
        $order->user->notify(new OrderCompletedForUserNotification($order->channel->channel_name));

        // Mark the order as finished
        $order->markAsFinished();
    }
}
