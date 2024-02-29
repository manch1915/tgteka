<?php

namespace App\Services\WebSocketMessageProviders;

use App\Models\Conversation;
use App\Services\Censure;
use Ratchet\ConnectionInterface;

class NewMessageHandler extends WebSocketMessageProvider
{

    #[\Override] public function sendMessage(array $data, array $userConnections): void
    {
        try {
            $auth_id = $data['auth_id'];
            $message = $data['message'];
            $conversation_id = $data['conversation_id'];
            $username = $data['username'];

            $conversation = Conversation::findOrFail($conversation_id);

            $recipientId = ($conversation->user_one === $auth_id) ? $conversation->user_two : $conversation->user_one;

            $message = Censure::replace($message);

            $messageObject = $this->messageFactory->createPersonalChatMessage($auth_id, $message, $conversation_id, $username);

            $this->personalChatRepository->save($auth_id, $conversation_id, $message);

            if (!isset($userConnections[$recipientId])) {
                return;
            }

            $userConnections[$recipientId]->send(json_encode($messageObject));

        } catch (Exception $e) {
            error_log('NewMessageHandler error: ' . $e->getMessage());
        }
    }
}
