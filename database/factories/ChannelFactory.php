<?php

namespace Database\Factories;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

class ChannelFactory extends Factory
{
    protected $model = Channel::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'channel_url' => $this->faker->url,
            'channel_name' => $this->faker->company,
            'telegram_username' => $this->faker->userName,
            'avatar' => $this->faker->imageUrl(),
            'topic' => $this->faker->word,
            'language' => $this->faker->languageCode,
            'description' => $this->faker->text(300),
            'format_one' => $this->faker->boolean,
            'format_two' => $this->faker->boolean,
            'format_three' => $this->faker->boolean,
            'no_deletion' => $this->faker->boolean,
            'repost' => $this->faker->boolean,
            'repeat_discount' => $this->faker->randomElement([0, 10, 20, 30, 50]),
            'score' => $this->faker->randomFloat(NULL, 0, 1),
            'rating' => $this->faker->randomFloat(NULL, 0, 5),
            'status' => $this->faker->randomElement(['pending', 'accepted', 'declined']),
        ];
    }
}
