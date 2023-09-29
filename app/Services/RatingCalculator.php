<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;

class RatingCalculator
{

    public function calculate(Order $order): void
    {
        $channel = $order->channel;

        $channel->rating = $this->calculateRating($order);
        $channel->score = $this->calculateScore($order);

        $channel->save();
    }

    private function calculateRating(Order $order): float
    {
        // You can also use DB query to make it faster, this is example with Collection methods
        $start = Carbon::now()->subYear();
        $orders = $order->channel->orders()->where('created_at', '>', $start)->get();

        $rating = $orders->sum(function ($order) {
            $pointsForOrder = 1;

            $pointsForVolume = $order->total / 1000 * 0.1;

            $pointsForRating = 0;
            if (!is_null($order->client_rating)) {
                if ($order->client_rating === 5) {
                    $pointsForRating = 5 * ($order->total / 10000);
                } elseif ($order->client_rating === 4) {
                    $pointsForRating = 4 * ($order->total / 10000) * 0.5;
                } elseif ($order->client_rating === 3) {
                    $pointsForRating = 3 * ($order->total / 10000) * 0.1;
                } elseif ($order->client_rating === 2) {
                    $pointsForRating = 2 * ($order->total / 10000) * -0.5;
                } elseif ($order->client_rating === 1) {
                    $pointsForRating = 1 * ($order->total / 10000) * -1;
                }
            }

            return $pointsForOrder + $pointsForVolume + $pointsForRating;
        });

        return $rating;
    }

    private function calculateScore(Order $order): float
    {

        $start = Carbon::now()->subYear();
        $orders = $order->channel->orders()->where('created_at', '>', $start)->get();

        $score = $orders->avg('client_rating');

        return $score;
    }
}
