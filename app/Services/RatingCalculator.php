<?php

namespace App\Services;

use Carbon\Carbon;

class RatingCalculator
{
    public function calculate(object $orderItem): void
    {
        $channel = $orderItem->channel;

        $channel->rating = $this->calculateRating($orderItem);
        $channel->score = $this->calculateScore($orderItem);

        $channel->save();
    }

    private function calculateRating(object $orderItem): float
    {
        $start = Carbon::now()->subYear();

        $orderItems = $orderItem->channel->orderItems()
            ->where('created_at', '>', $start)
            ->where('status', 'accepted')
            ->get();

        $rating = $orderItems->sum(function ($orderItem) {
            $pointsForOrder = 1.0;
            $total = $orderItem->price * $orderItem->count;
            $pointsForVolume = $total / 1000 * 0.1;
            $clientRating = $orderItem->client_rating ?? 0;
            $pointsForRating = $this->calculatePointsForRating($clientRating, $total);

            return $pointsForOrder + $pointsForVolume + $pointsForRating;
        });

        $bonusScore = $this->calculateBonusScore($orderItem->channel);

        return $rating + $bonusScore;
    }

    private function calculatePointsForRating(int $rating, float $total): float
    {
        return match ($rating) {
            5 => 5 * ($total / 10000),
            4 => 4 * ($total / 10000) * 0.5,
            3 => 3 * ($total / 10000) * 0.1,
            2 => 2 * ($total / 10000) * -0.5,
            1 => 1 * ($total / 10000) * -1,
            default => 0,
        };
    }

    private function calculateScore(object $orderItem): float
    {
        $start = Carbon::now()->subYear();
        $orders = $orderItem->channel->orders()->where('created_at', '>', $start)->get();

        $score = $orders->avg('client_rating') ?? 0;

        return $score;
    }

    private function calculateBonusScore(object $channel): float
    {
        if (
            $channel->format_one_price !== 0 ||
            $channel->format_two_price !== 0 ||
            $channel->format_three_price !== 0 ||
            $channel->no_deletion_price !== 0 ||
            $channel->repost_price !== 0
        ) {
            return 1.0;
        }

        return 0;
    }
}
