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

    public function saveMessage(int $senderId, int $ticketId, string $message, string $contentType): void
    {
        try {
            if ('image' === $contentType && !empty(trim($message))) {

                $supportMessage = SupportMessage::create([
                    'sender_id' => $senderId,
                    'ticket_id' => $ticketId,
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

                $supportMessage
                    ->addMediaFromBase64($message)
                    ->usingFileName(uniqid() . "." . $extension)
                    ->toMediaCollection('support_message_images');

            } else if (!empty(trim($message))) {
                // Here, we handle the text messages
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
