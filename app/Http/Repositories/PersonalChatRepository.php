<?php

namespace App\Http\Repositories;


use App\Models\ConversationMessages;

class PersonalChatRepository
{
    public function save(int $user_id, int $conversation_id, string $message, string $contentType): void
    {
        try {
            if ('image' === $contentType && !empty(trim($message))) {

                $conversationMessages = ConversationMessages::create([
                    'user_id' => $user_id,
                    'conversation_id' => $conversation_id,
                ]);

                $extension = '';

                if (preg_match('/^data:(\w+\/\w+);base64,/', $message, $type)) {
                    $mimeType = $type[1]; // image/jpeg, image/png etc.
                    $message = substr($message, strpos($message, ',') + 1);

                    $extension = [
                        'image/jpeg' => 'jpg',
                        'image/png' => 'png',
                        'image/gif' => 'gif',
                        // Continue to add more mappings if needed
                    ][$mimeType] ?? '';

                    if (empty($extension)) {
                        throw new \Exception('Unable to determine file extension from MIME type');
                    }
                }

                $conversationMessages
                    ->addMediaFromBase64($message)
                    ->usingFileName(uniqid() . "." . $extension)
                    ->toMediaCollection('personal_message_images');

            }else if (!empty(trim($message))) {

                ConversationMessages::create([
                    'user_id' => $user_id,
                    'conversation_id' => $conversation_id,
                    'message' => $message,
                ]);

            }

        } catch (\Exception $e) {
            echo "Error while saving personal chat message: " . $e->getMessage() . "\n";
        }
    }
}
