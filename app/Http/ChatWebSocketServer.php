<?php

namespace App\Http;

use App\Http\Factories\MessageFactory;
use App\Http\Repositories\PersonalChatRepository;
use App\Http\Repositories\SupportChatRepository;
use App\Models\Moderator;
use Illuminate\Support\Facades\Log;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

class ChatWebSocketServer implements MessageComponentInterface
{
    protected SplObjectStorage $clients;
    protected array $userConnections;
    protected PersonalChatRepository $personalChatRepository;
    protected SupportChatRepository $supportChatRepository;
    protected MessageFactory $messageFactory;

    public function __construct(PersonalChatRepository $personalChatRepository, SupportChatRepository $supportChatRepository ,MessageFactory $messageFactory)
    {
        $this->clients = new \SplObjectStorage;
        $this->userConnections = [];
        $this->personalChatRepository = $personalChatRepository;
        $this->supportChatRepository = $supportChatRepository;
        $this->messageFactory = $messageFactory;
    }

    public function onOpen(ConnectionInterface $conn): void
    {
        $this->clients->attach($conn);
        $clientId = spl_object_hash($conn);
        echo "New connection! ({$clientId})\n";

        parse_str($conn->httpRequest->getUri()->getQuery(), $queryArray);
        $userId = $queryArray['userid'] ?? null;
        if ($userId !== null) {
            $this->userConnections[$userId] = $conn;
        }
    }

    public function onMessage(ConnectionInterface $from, $msg): void
    {
        $data = json_decode($msg, true);
        Log::info($data);
        if(json_last_error() !== JSON_ERROR_NONE) {
            Log::error("JSON decode error: " . json_last_error_msg());
        } else {
            $this->handleChatMessage($data);
        }
    }

    public function onClose(ConnectionInterface $conn): void
    {
        $this->clients->detach($conn);
        $disconnectedUser = $this->findAndRemoveUserConnection($conn);
        $clientId = spl_object_hash($conn);
        Log::info("Connection {$clientId} has disconnected");
        // Consider adding some logic here if you want to handle when a user disconnects
    }

    public function onError(ConnectionInterface $conn, \Exception $e): void
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    private function handleChatMessage(array $data): void
    {
        $type = $data['type'] ?? 'chat';
        if($type === 'support') {
            $this->sendSupportChatMessage($data['sender_id'], $data['message'], $data['title'], $data['ticket_id']);
        } else {
            $this->sendPersonalChatMessage($data['sender_id'], $data['recipient_id'], $data['message']);
        }
    }

    private function sendSupportChatMessage(int $senderId, string $message, string $title, ?int $ticketId): void
    {

        $ticketBackId = $ticketId;
        if ($ticketBackId === null) {
            Log::info('ticked saved');
            $ticketBackId = $this->supportChatRepository->saveTicket($senderId, $title, $message);
        } else {
            $this->supportChatRepository->saveMessage($senderId, $ticketId, $message);
        }

        $messageObject = $this->messageFactory->createSupportChatMessage($senderId, $ticketBackId, $message);

        $moderators = Moderator::all();
        foreach($moderators as $moderator) {
            if (isset($this->userConnections[$moderator['user_id']])) {
                echo 'message sent';
                $this->userConnections[$moderator['user_id']]->send(json_encode($messageObject));
            }
        }

    }

    private function sendPersonalChatMessage(int $senderId, int $recipientId, string $message): void
    {
        $messageObject = $this->messageFactory->createPersonalChatMessage($senderId, $message);

        if (!isset($this->userConnections[$recipientId])) {
            return;
        }

        $this->userConnections[$recipientId]->send(json_encode($messageObject));

        $this->personalChatRepository->save($senderId, $recipientId, $message);
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
