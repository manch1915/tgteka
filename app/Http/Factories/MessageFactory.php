<?php

namespace App\Http\Factories;

class MessageFactory
{
    public function createPersonalChatMessage(int $user_id, string $message, int $conversation_id, string $username): array
    {
        return [
            'user_id' => $user_id,
            'message' => $message,
            'conversation_id' => $conversation_id,
            'username' => $username,
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
