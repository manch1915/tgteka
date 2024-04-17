<?php

namespace App\Http\Factories;

class MessageFactory
{
    public function createPersonalChatMessage(int $user_id, string $message, int $conversation_id, string $username,  string $contentType): array
    {
        return [
            'user_id' => $user_id,
            'message' => $message,
            'conversation_id' => $conversation_id,
            'username' => $username,
            'content_type' => $contentType
        ];
    }
    public function createSupportChatMessage(int $senderId, int $ticketId, string $message, string $contentType): array
    {
        return [
            'sender_id' => $senderId,
            'ticket_id' => $ticketId,
            'message' => $message,
            'content_type' => $contentType
        ];
    }
}
