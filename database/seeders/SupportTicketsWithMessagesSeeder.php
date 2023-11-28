<?php

namespace Database\Seeders;

use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Database\Seeder;

class SupportTicketsWithMessagesSeeder extends Seeder
{
    private const MIN_MESSAGES = 10;
    private const MAX_MESSAGES = 20;
    private const TICKETS_AMOUNT = 50;

    public function run(): void
    {
        $this->createTicketsWithRespectiveMessages();
    }

    private function createTicketsWithRespectiveMessages(): void
    {
        for ($i = 0; $i < self::TICKETS_AMOUNT; $i++) {
            $supportTicket = SupportTicket::factory()->create();
            $this->createSupportMessagesForTicket($supportTicket);
        }
    }

    private function createSupportMessagesForTicket(SupportTicket $ticket): void
    {
        $users = $this->createUsersForTicket();
        $messagesCount = rand(self::MIN_MESSAGES, self::MAX_MESSAGES);

        for ($i = 0; $i < $messagesCount; $i++) {
            $senderId = $this->getSenderIdBasedOn_iteration($users, $i);
            SupportMessage::factory()->create(['sender_id' => $senderId, 'ticket_id' => $ticket->id]);
        }
    }

    private function createUsersForTicket(): array
    {
        return [
            User::factory()->create(),
            User::factory()->create()
        ];
    }

    private function getSenderIdBasedOn_iteration(array $users, int $iteration): int
    {
        return $users[$iteration % 2]->id;
    }
}
