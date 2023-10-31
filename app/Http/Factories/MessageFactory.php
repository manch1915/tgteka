<?php

namespace App\Http\Factories;

class MessageFactory
{
    public function createPersonalChatMessage(int $senderId, string $message): array
    {
        return [
            'sender_id' => $senderId,
            'message' => $message,
        ];
    }
    public function createSupportChatMessage(int $senderId, int $ticketId, string $message): array
    {
        return [
            'sender_id' => $senderId,
            'ticket_id' => $ticketId,
            'message' => $message
        ];
    }
}
