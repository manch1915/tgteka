<?php

namespace Database\Factories;

use App\Models\ConversationMessages;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ConversationMessagesFactory extends Factory
{
    protected $model = ConversationMessages::class;

    public function definition(): array
    {
        return [
            'is_seen' => $this->faker->boolean(),
            'user_id' => $this->faker->randomNumber(),
            'conversation_id' => $this->faker->randomNumber(),
            'message' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
