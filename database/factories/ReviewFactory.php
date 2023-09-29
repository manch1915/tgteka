<?php

namespace Database\Factories;

use App\Models\Channel;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'channel_id' => Channel::factory(),
            'review_text' => $this->faker->paragraph,
        ];
    }
}
