<?php

namespace Database\Factories;

use App\Models\Channel;
use App\Models\Order;
use App\Models\Pattern;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'pattern_id' => Pattern::factory(),
            'description' => $this->faker->text(100),
            'status' => $this->faker->randomElement(['pending', 'accepted', 'declined']),
        ];
    }
}
