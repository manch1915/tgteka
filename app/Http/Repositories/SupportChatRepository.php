<?php

namespace App\Http\Repositories;

use App\Models\SupportMessage;
use App\Models\SupportTicket;

class SupportChatRepository
{
    public function saveTicket(int $senderId, string $title, string $message): void
    {
        try {
            $createdTicket = SupportTicket::create([
                'sender_id' => $senderId,
                'title' => $title,
            ]);

            SupportMessage::create([
                'sender_id' => $senderId,
                'ticket_id' => $createdTicket->id,
                'message' => $message,
            ]);

        } catch (\Exception $e) {
            echo "Error while saving support ticket: " . $e->getMessage() . "\n";
        }
    }

    public function saveMessage(int $senderId, int $ticketId, string $message): void
    {
        try {
            SupportMessage::create([
                'sender_id' => $senderId,
                'ticket_id' => $ticketId,
                'message' => $message,
            ]);

        } catch (\Exception $e) {
            echo "Error while saving support message: " . $e->getMessage() . "\n";
        }
    }

    public function ticketExists(int $ticketId): bool
    {
        try {
            return SupportTicket::where('id', $ticketId)->exists();
        } catch (\Exception $e) {
            echo "Error while checking if ticket exists: " . $e->getMessage() . "\n";
            return false;
        }
    }

    public function getMessagesForTicket(int $ticketId): array
    {
        return SupportMessage::where('ticket_id', $ticketId)->get()->toArray();
    }
}
