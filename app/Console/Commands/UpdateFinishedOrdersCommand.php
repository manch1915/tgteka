<?php

namespace App\Console\Commands;

use App\Notifications\OrderCompletedForAdminNotification;
use App\Notifications\OrderCompletedForUserNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Order;

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
    protected $description = 'Update the status of finished orders to "finish"';

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
                    $channelAdmin = $order->channel->user;
                    $channelAdmin->balance += $order->price;
                    $channelAdmin->save();

                    $channelAdmin->notify(new OrderCompletedForAdminNotification($order->price, $order->channel->channel_name));

                    $order->user->notify(new OrderCompletedForUserNotification($order->channel->channel_name));

                    $order->update(['status' => 'finished']);
                }
            });

        $this->info('Orders have been updated successfully!');
    }

}
