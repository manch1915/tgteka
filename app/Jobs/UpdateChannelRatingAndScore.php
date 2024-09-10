<?php

namespace App\Jobs;

use App\Models\Channel;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class UpdateChannelRatingAndScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $chunkSize = 200;

        // Eager load 'orders' and 'reviews' relationships to avoid lazy loading
        Channel::where('status', 'accepted')
            ->with(['orders' => function ($query) {
                $query->where('created_at', '>=', Carbon::now()->subDays(365))
                    ->where('status', 'finished');
            }, 'reviews']) // Eager load 'reviews' for calculating channel rating
            ->chunk($chunkSize, function ($channels) {
                foreach ($channels as $channel) {
                    // Calculate score based on orders from the last 365 days
                    $lastYearOrders = $channel->orders;
                    $score = 0;

                    foreach ($lastYearOrders as $order) {
                        $score += 1; // Score for completing an order

                        // Add score based on order price
                        $score += ($order->price / 1000) * 0.1;

                        // If reviews are related to channels, calculate from channel's reviews
                        // Review rating logic is moved to channel reviews instead of orders
                    }

                    // Update channel score
                    $channel->score = $score;

                    // Calculate rating from all reviews related to the channel
                    $reviews = $channel->reviews;
                    if ($reviews->count()) {
                        $rating = $reviews->sum('rating') / $reviews->count();
                        $channel->rating = $rating;
                    }

                    $channel->save();
                }
            });
    }

    private function getScoreByRating($rating, $price): float|int
    {
        return match ($rating) {
            5 => $price / 10000,
            4 => ($price / 10000) * 0.5,
            3 => ($price / 10000) * 0.1,
            2 => ($price / 10000) * -0.5,
            1 => ($price / 10000) * -1,
            default => 0,
        };
    }
}
