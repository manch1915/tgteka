<?php

namespace Database\Factories;

use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SupportMessageFactory extends Factory
{
    protected $model = SupportMessage::class;

    public function definition(): array
    {
        return [
            'message' => $this->faker->text,
            'sender_id' => User::factory(),
            'ticket_id' => SupportTicket::factory(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
    }
}
