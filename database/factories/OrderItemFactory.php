<?php

namespace Database\Factories;

use App\Models\Channel;
use App\Models\Format;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        return [
            'count' => $this->faker->randomNumber(),
            'price' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'order_id' => Order::factory(),
            'channel_id' => Channel::factory(),
            'format_id' => Format::factory(),
        ];
    }
}
