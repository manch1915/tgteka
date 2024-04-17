<?php

namespace App\Services\WebSocketMessageProviders;

use App\Models\Conversation;
use App\Services\Censure;
use Ratchet\ConnectionInterface;

class NewMessageHandler extends WebSocketMessageProvider
{

    #[\Override] public function sendMessage(array $data, array $userConnections, array $userMainConnections): void
    {
        try {
            $auth_id = $data['auth_id'];
            $message = $data['message'];
            $conversation_id = $data['conversation_id'];
            $username = $data['username'];
            $contentType = $data['content_type'];

            $conversation = Conversation::findOrFail($conversation_id);

            $recipientId = ($conversation->user_one === $auth_id) ? $conversation->user_two : $conversation->user_one;

            if ($contentType == 'text'){
                $message = Censure::replace($message);
            }

            $messageObject = $this->messageFactory->createPersonalChatMessage($auth_id, $message, $conversation_id, $username, $contentType);

            $this->personalChatRepository->save($auth_id, $conversation_id, $message, $contentType);

            $notification = [
                'type' => 'notification',
                'content' => 'You have a new message',
            ];

            if (isset($userMainConnections[$recipientId])) {
                $userMainConnections[$recipientId]->send(json_encode($notification));
            }

            if (!isset($userConnections[$recipientId])) {
                return;
            }

            $userConnections[$recipientId]->send(json_encode($messageObject));

        } catch (Exception $e) {
            error_log('NewMessageHandler error: ' . $e->getMessage());
        }
    }
}
