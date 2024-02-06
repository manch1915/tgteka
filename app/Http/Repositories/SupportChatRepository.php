<?php

namespace App\Http\Repositories;

use App\Models\SupportMessage;
use App\Models\SupportTicket;

class SupportChatRepository
{
    public function saveTicket(int $senderId, string $title, string $message): int
    {
        try {
            $createdTicket = SupportTicket::create([
                'sender_id' => $senderId,
                'title' => $title,
            ]);

            if (!empty(trim($message))) {
                SupportMessage::create([
                    'sender_id' => $senderId,
                    'ticket_id' => $createdTicket->id,
                    'message' => $message,
                ]);
            }

            return $createdTicket->id;
        } catch (\Exception $e) {
            echo "Error while saving support ticket: " . $e->getMessage() . "\n";
        }
        return 0;
    }

    public function saveMessage(int $senderId, int $ticketId, string $message): void
    {
        try {
            if (!empty(trim($message))) {
                SupportMessage::create([
                    'sender_id' => $senderId,
                    'ticket_id' => $ticketId,
                    'message' => $message,
                ]);
            }

        } catch (\Exception $e) {
            echo "Error while saving support message: " . $e->getMessage() . "\n";
        }
    }

}
