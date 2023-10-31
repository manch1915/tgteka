<?php

namespace App\Http\Factories;

class MessageFactory
{
    public function createChatMessage(int $senderId, string $message): array
    {
        return [
            'sender_id' => $senderId,
            'message' => $message,
        ];
    }
}
