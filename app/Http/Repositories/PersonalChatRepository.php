<?php

namespace App\Http\Repositories;

use App\Models\Chat;

class PersonalChatRepository
{
    public function save(int $senderId, int $recipientId, string $message): void
    {
        try {
            $chat = new Chat([
                'sender_id' => $senderId,
                'recipient_id' => $recipientId,
                'message' => $message,
            ]);

            $chat->save();
        } catch (\Exception $e) {
            echo "Error while saving personal chat message: " . $e->getMessage() . "\n";
        }
    }

    public function getMessagesForUser(int $userId): array
    {
        return Chat::where(function($query) use ($userId) {
            $query->where('sender_id', $userId)
                ->orWhere('recipient_id', $userId);
        })->get()->toArray();
    }
}
