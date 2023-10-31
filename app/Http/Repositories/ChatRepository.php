<?php

namespace App\Http\Repositories;

use App\Models\Chat;

class ChatRepository
{
    public function save(int $senderId, int|null $recipientId, string $message, string $type): void
    {
        try {
            $chat = new Chat([
                'sender_id' => $senderId,
                'recipient_id' => $recipientId,
                'type' => $type,
                'message' => $message,
            ]);

            $chat->save();
        } catch (\Exception $e) {
            echo "Error while saving chat message: " . $e->getMessage() . "\n";
        }
    }

    public function getMessagesForUser(int $userId): array
    {
        return Chat::where('sender_id', $userId)
            ->orWhere('recipient_id', $userId)
            ->get()
            ->toArray();
    }
}
