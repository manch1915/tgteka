<?php

namespace App\Http;

use App\Http\Factories\MessageFactory;
use App\Http\Repositories\ChatRepository;
use App\Models\Moderator;
use Illuminate\Support\Facades\Log;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

class ChatWebSocketServer implements MessageComponentInterface
{
    protected SplObjectStorage $clients;
    protected array $userConnections;
    protected ChatRepository $chatRepository;
    protected MessageFactory $messageFactory;

    public function __construct(ChatRepository $chatRepository, MessageFactory $messageFactory)
    {
        $this->clients = new \SplObjectStorage;
        $this->userConnections = [];
        $this->chatRepository = $chatRepository;
        $this->messageFactory = $messageFactory;
    }

    public function onOpen(ConnectionInterface $conn): void
    {
        $this->clients->attach($conn);
        $clientId = spl_object_hash($conn);
        echo "New connection! ({$clientId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg): void
    {
        $data = json_decode($msg, true);
        $this->handleChatMessage($data);
    }

    public function onClose(ConnectionInterface $conn): void
    {
        $this->clients->detach($conn);
        $disconnectedUser = $this->findAndRemoveUserConnection($conn);
        $clientId = spl_object_hash($conn);
        echo "Connection {$clientId} has disconnected\n";
        if ($disconnectedUser !== null) {
            // Handle any necessary cleanup or notifications related to the disconnected user
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e): void
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    private function sendMessage(int $senderId, $recipientId, string $message, string $type): void
    {
        $messageObject = $this->messageFactory->createChatMessage($senderId, $message);
        if($type == 'support'){
            $moderators = Moderator::all();
            foreach($moderators as $moderator){
                // Send message to each moderator
                Log::info($moderator);
                if (isset($this->userConnections[$moderator['user_id']])) {
                    $this->userConnections[$moderator['user_id']]->send(json_encode($messageObject));
                }
            }
            $this->chatRepository->save($senderId, null, $message, $type);
        } else {
            if (!isset($this->userConnections[$recipientId])) {
                return;
            }

            $this->userConnections[$recipientId]->send(json_encode($messageObject));
        }
        $this->chatRepository->save($senderId, $recipientId, $message, $type);
    }

    private function handleChatMessage(array $data): void
    {
        $recipientId = $data['type'] == 'support' ? null : $data['recipient_id'];
        $this->sendMessage($data['sender_id'], $recipientId, $data['message'], $data['type']);
    }

    private function findAndRemoveUserConnection(ConnectionInterface $conn): ?int
    {
        foreach ($this->userConnections as $userId => $connection) {
            if ($connection !== $conn) {
                continue;
            }
            unset($this->userConnections[$userId]);
            return $userId;
        }
        return null;
    }
}

