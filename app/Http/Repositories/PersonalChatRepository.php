<?php

namespace App\Http\Repositories;


use App\Models\ConversationMessages;

class PersonalChatRepository
{
    public function save(int $user_id, int $conversation_id, string $message): void
    {
        try {
            $chat = new ConversationMessages([
                'user_id' => $user_id,
                'conversation_id' => $conversation_id,
                'message' => $message,
            ]);

            $chat->save();
        } catch (\Exception $e) {
            echo "Error while saving personal chat message: " . $e->getMessage() . "\n";
        }
    }
}
