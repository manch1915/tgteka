<?php

namespace App\Services;

use Carbon\Carbon;

class RatingCalculator
{

    public function calculate($orderItem): void
    {
        $channel = $orderItem->channel;

        $channel->rating = $this->calculateRating($orderItem);
        $channel->score = $this->calculateScore($orderItem);

        $channel->save();
    }

    private function calculateRating($orderItem): float
    {

        $start = Carbon::now()->subYear();
        $orderItems = $orderItem->channel->orderItems()->where('created_at', '>', $start)->where('status', 'accepted')->get();

        $rating = $orderItems->sum(function ($orderItem) {
            $pointsForOrder = 1;
            $total = $orderItem->price * $orderItem->count;

            $pointsForVolume = $total / 1000 * 0.1;

            $pointsForRating = 0;
            if (!is_null($orderItem->client_rating)) {
                if ($orderItem->client_rating === 5) {
                    $pointsForRating = 5 * ($total / 10000);
                } elseif ($orderItem->client_rating === 4) {
                    $pointsForRating = 4 * ($total / 10000) * 0.5;
                } elseif ($orderItem->client_rating === 3) {
                    $pointsForRating = 3 * ($total / 10000) * 0.1;
                } elseif ($orderItem->client_rating === 2) {
                    $pointsForRating = 2 * ($total / 10000) * -0.5;
                } elseif ($orderItem->client_rating === 1) {
                    $pointsForRating = 1 * ($total / 10000) * -1;
                }
            }

            return $pointsForOrder + $pointsForVolume + $pointsForRating;
        });

        $bonusScore = $this->calculateBonusScore($orderItem->channel);

        return $rating + $bonusScore;
    }

    private function calculateScore($orderItem): float
    {

        $start = Carbon::now()->subYear();
        $orders = $orderItem->channel->orders()->where('created_at', '>', $start)->get();

        $score = $orders->avg('client_rating');

        return $score;
    }

    private function calculateBonusScore($channel): float
    {
        $bonusScore = 0;

        if (
            $channel->format_one_price !== 0 ||
            $channel->format_two_price !== 0 ||
            $channel->format_three_price !== 0 ||
            $channel->no_deletion_price !== 0 ||
            $channel->repost_price !== 0
        ) {
            $bonusScore = 1.0;
        }

        return $bonusScore;
    }
}
