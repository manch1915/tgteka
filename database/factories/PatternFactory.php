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
            'user_id' => User::factory(),
            'media' => $this->faker->imageUrl(),
            'body' => $this->faker->text(100),
            'status' => $this->faker->randomElement(['pending', 'accepted', 'declined'])
        ];
    }
}
