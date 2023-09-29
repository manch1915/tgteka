<?php

namespace App\Http;

use App\Http\Factories\MessageFactory;
use App\Http\Repositories\ChatRepository;
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

        match ($data['type'] ?? null) {
            'init' => $this->handleInitMessage($data, $from),
            'chat' => $this->handleChatMessage($data),
            'get_messages' => $this->handleGetMessages($data),
        };
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

    public function onError(ConnectionInterface $conn, Exception $e): void
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    private function sendMessage(int $senderId, int $recipientId, string $message): void
    {
        if (!isset($this->userConnections[$recipientId])) {
            return;
        }

        $messageObject = $this->messageFactory->createChatMessage($senderId, $message);
        $this->userConnections[$recipientId]->send(json_encode($messageObject));
        $this->chatRepository->save($senderId, $recipientId, $message);
    }

    private function sendMessagesToUser(int $userId, array $messages): void
    {
        if (!isset($this->userConnections[$userId])) {
            return;
        }

        foreach ($messages as $message) {
            $messageObject = $this->messageFactory->createChatMessage($message['sender_id'], $message['message']);
            $this->userConnections[$userId]->send(json_encode($messageObject));
        }
    }

    private function handleInitMessage(array $data, ConnectionInterface $from): void
    {
        $this->userConnections[$data['user_id']] = $from;
        echo "User (ID: {$data['user_id']}) connected!\n";
    }

    private function handleChatMessage(array $data): void
    {
        $this->sendMessage($data['sender_id'], $data['recipient_id'], $data['message']);
    }

    private function handleGetMessages(array $data): void
    {
        $messages = $this->chatRepository->getMessagesForUser($data['user_id']);
        $this->sendMessagesToUser($data['user_id'], $messages);
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

