<?php

namespace Tests\Feature;

use App\Models\Channel;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\OrderService;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    public function test_checkRepeatDiscount()
    {
        $service = new OrderService();

        // Create a User
        $user = User::factory()->create();

        // Create some Channel records
        $channels = Channel::factory()->count(3)->create();

        $channelsArray = $channels->map(function ($channel) {
            return ['id' => $channel->id];
        })->toArray();

        // Create some Order records with the 'accepted' status for this user and specific channels
        $orders = Order::factory()->count(2)->state([
            'user_id' => $user->id,
            'status' => 'accepted',
        ])->has(OrderItem::factory()->count(3)->state(function () use ($channels) {
            return [
                'channel_id' => $channels->random()->id,
            ];
        }), 'items')->create();

        $discount = $service->checkRepeatDiscount($user->id, $channelsArray);

        // Assuming calculateTotalSum just returns sum of channel ids for test simplicity. Adjust according to real implementation.
        $expectedDiscount = count($channelsArray) * count($orders) * 10 / 100;

        $this->assertEquals($expectedDiscount, $discount);
    }

}
