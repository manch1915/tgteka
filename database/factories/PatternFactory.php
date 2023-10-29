<?php

namespace Database\Factories;

use App\Models\Pattern;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatternFactory extends Factory
{
    protected $model = Pattern::class;

    public function definition(): array
    {
        return [
            'user_id' => 1,
            'media' => $this->faker->imageUrl(),
            'body' => '<p>fake factory</p>>',
            'status' => $this->faker->randomElement(['pending', 'accepted', 'declined']),
            'created_at' => $this->faker->dateTimeThisYear()
        ];
    }
}
