<?php

namespace Database\Factories;

use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SupportTicketFactory extends Factory
{
    protected $model = SupportTicket::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->firstNameMale,
            'sender_id' => User::factory(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
    }
}
