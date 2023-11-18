<?php

namespace Database\Seeders;

use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Database\Seeder;

class SupportTicketsWithMessagesSeeder extends Seeder
{
    public function run(): void
    {
        for($i = 0; $i<50; $i++) {
            $supportTicket = SupportTicket::factory()->create();
            $userOne = User::factory()->create();
            $userTwo = User::factory()->create();
            $countMessages = rand(10, 20);
            for($j = 0; $j < $countMessages; $j++) {
                SupportMessage::factory()->create([
                    'sender_id' => $i % 2 == 0 ? $userOne->id : $userTwo->id,
                    'ticket_id' => $supportTicket->id
                ]);
            }
        }
    }
}
