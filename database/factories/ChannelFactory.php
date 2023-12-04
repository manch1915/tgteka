<?php

namespace Database\Factories;

use App\Models\Channel;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ChannelFactory extends Factory
{
    protected $model = Channel::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'url' => $this->faker->unique()->url(),
            'channel_name' => $this->faker->words(3, true),
            'topic_id' => Topic::factory(),
            'type' => $this->faker->randomElement(['chat', 'channel']),
            'language' => $this->faker->languageCode(),
            'description' => $this->faker->optional()->realText(500),
            'subscribers_source' => $this->faker->optional()->realText(400),
            'format_one_price' => $this->faker->randomNumber(4),
            'format_two_price' => $this->faker->randomNumber(4),
            'format_three_price' => $this->faker->randomNumber(4),
            'no_deletion_price' => $this->faker->randomNumber(4),
            'repost_price' => $this->faker->boolean(),
            'repeat_discount' => $this->faker->randomElement([10, 20, 30, 40, 50]),
            'score' => $this->faker->randomFloat(1, 0, 5), // Assuming score is between 0 and 5
            'rating' => $this->faker->randomFloat(1, 0, 5), // Assuming rating is between 0 and 5
            'likes_count' => $this->faker->numberBetween(0, 1000000),
            'views_count' => $this->faker->numberBetween(0, 10000000),
            'status' => $this->faker->randomElement(['pending', 'accepted', 'declined']),
        ];
    }
}
