<?php

namespace Database\Factories;

use App\Models\Moderator;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ModeratorFactory extends Factory
{
    protected $model = Moderator::class;

    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
