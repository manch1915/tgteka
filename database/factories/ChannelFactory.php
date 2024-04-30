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
            'user_id' => \App\Models\User::factory(),
            'channel_name' => $this->faker->words(2, true),
            'description' => $this->faker->text(150),
            'subscribers_source' => $this->faker->url,
            'topic_id' => \App\Models\Topic::inRandomOrder()->first()->id,
            'type' => 'channel',
            'url' => '@' . $this->faker->words(1, true),
            'repeat_discount' => $this->faker->randomElement([0, 10, 20, 30, 50]),
            'male_percentage' => $this->faker->numberBetween(0, 100),
            'format_one_price' => $this->faker->numberBetween(1, 1000),
            'format_two_price' => $this->faker->numberBetween(1, 1000),
            'format_three_price' => $this->faker->numberBetween(1, 1000),
            'no_deletion_price' => $this->faker->numberBetween(1, 1000),
            'cpm' => $this->faker->numberBetween(1, 1000)
        ];
    }
}
