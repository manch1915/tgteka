<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Channel;
use Illuminate\Support\Carbon;

class UpdateChannelRatingAndScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $chunkSize = 200;

        Channel::where('status', 'accepted')
            ->with(['orders' => function ($query) {
                $query->where('created_at', '>=', Carbon::now()->subDays(365))
                    ->where('status', 'finished')
                    ->with('review');
            }, 'reviews'])
            ->chunk($chunkSize, function ($channels) {
                foreach ($channels as $channel) {
                    // Calculate score based on orders from the last 365 days
                    $lastYearOrders = $channel->orders;
                    $score = 0;

                    foreach ($lastYearOrders as $order) {
                        $score += 1; // Score for completing an order

                        // Add score based on order price
                        $score += ($order->price / 1000) * 0.1;

                        // Add score based on review rating
                        if ($order->review) {
                            $score += $this->getScoreByRating($order->review->rating, $order->price);
                        }
                    }

                    // Update channel score
                    $channel->score = $score;

                    // Calculate rating from all reviews
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
