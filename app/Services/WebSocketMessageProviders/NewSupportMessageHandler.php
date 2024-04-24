<?php

namespace App\Services\WebSocketMessageProviders;

use App\Models\Moderator;
use App\Models\SupportTicket;
use App\Services\Censure;

class NewSupportMessageHandler extends WebSocketMessageProvider
{

    #[\Override] public function sendMessage(array $data, array $userConnections, array $userMainConnections): void
    {
        $senderId = $data['sender_id'];
        $message = $data['message'];
        $title = $data['title'];
        $ticketId = $data['ticket_id'];
        $contentType = $data['content_type'];

        if ($contentType == 'text'){
            $message = Censure::replace($message);
        }

        $ticketBackId = $ticketId ?? $this->supportChatRepository->saveTicket($senderId, $title, $message);

        if ($ticketId !== null) {
            $this->supportChatRepository->saveMessage($senderId, $ticketId, $message, $contentType);
        }else{
            $response = [
                'type' => 10,
                'ticket_id' => $ticketBackId,
            ];

            if (isset($userConnections[$senderId])) {
                $userConnections[$senderId]->send(json_encode($response));
            }
        }

        // Get the SupportTicket model using the ticket id
        $supportTicket = SupportTicket::find($ticketBackId);

        $messageObject = $this->messageFactory->createSupportChatMessage($senderId, $ticketBackId, $message, $contentType);

        $moderators = Moderator::all()->pluck('user_id')->toArray();

        if (in_array($senderId, $moderators)) {
            if (isset($userConnections[$supportTicket->sender_id])) {
                $userConnections[$supportTicket->sender_id]->send(json_encode($messageObject));
            }
        } else {
            foreach($moderators as $moderatorId) {
                if (isset($userConnections[$moderatorId])) {
                    $userConnections[$moderatorId]->send(json_encode($messageObject));
                }
            }
        }
    }
}
