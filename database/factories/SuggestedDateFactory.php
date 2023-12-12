<?php

namespace Database\Factories;

use App\Models\SuggestedDate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SuggestedDateFactory extends Factory
{
    protected $model = SuggestedDate::class;

    public function definition(): array
    {
        return [
            'suggested_post_date' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
