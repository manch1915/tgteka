<?php

namespace App\Services;

use App\Models\Channel;
use App\Models\Format;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTransaction;
use Illuminate\Http\Request;

class OrderService
{
    public function createOrder(Request $request): float|int|string
    {
        $totalSum = $this->calculateTotalSum($request->channels['_value']);

        $user = auth()->user();

        if ($user->balance >= $totalSum) {
            $user->decrementBalance($totalSum);
        } else {
            return 'You don\'t have enough money for this operation';
        }

        $order = $this->createOrderRecord($request);

        $this->createOrderItems($request->channels['_value'], $order);

        $this->createOrderTransaction($order, $totalSum);

        return $totalSum;
    }

    private function calculateTotalSum(array $channels): float|int
    {
        $totalSum = 0;

        foreach ($channels as $channel) {
            Channel::firstOrFail($channel['id']);
            $formatPrice = $channel[$channel['format']];
            $totalSum += $formatPrice * $channel['count'];
        }

        return $totalSum;
    }

    private function createOrderRecord(Request $request)
    {
        return Order::create([
            'user_id' => auth()->id(),
            'pattern_id' => $request->pattern_id,
            'description' => $request->description,
            'status' => 'pending',
        ]);
    }

    private function createOrderItems(array $channels, Order $order): void
    {
        foreach ($channels as $channel) {
            $formatName = ['format_one_price' => '1/24', 'format_two_price' => '2/48', 'format_three_price' => '3/72', 'no_deletion_price' => 'no_deletion'];

            $format = Format::where('name', $formatName[$channel['format']])->firstOrFail();

            OrderItem::create([
                'order_id' => $order->id,
                'channel_id' => $channel['id'],
                'format_id' => $format->id,
                'count' => $channel['count'],
                'price' => $channel[$channel['format']] * $channel['count'],
            ]);
        }
    }

    private function createOrderTransaction(Order $order, $totalSum): void
    {
        OrderTransaction::create([
            'user_id' => auth()->id(),
            'order_id' => $order->id,
            'amount' => -$totalSum,
        ]);
    }
}
