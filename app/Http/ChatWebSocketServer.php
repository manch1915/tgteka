<?php

namespace App\Http;

use App\Http\Factories\MessageFactory;
use App\Http\Repositories\PersonalChatRepository;
use App\Http\Repositories\SupportChatRepository;
use App\Models\Conversation;
use App\Models\Moderator;
use App\Models\SupportTicket;
use App\Services\Censure;
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
        if(json_last_error() !== JSON_ERROR_NONE) {
            Log::error("JSON decode error: " . json_last_error_msg());
        } else {
            $this->handleChatMessage($data);
        }
    }

    public function onClose(ConnectionInterface $conn): void
    {
        $this->clients->detach($conn);

        // Find the user associated with the connection and remove it.
        $userId = array_search($conn, $this->userConnections);
        if ($userId !== false) {
            unset($this->userConnections[$userId]);
        }

        $clientId = spl_object_hash($conn);
        Log::info("Connection {$userId} has disconnected");
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
            $this->sendPersonalChatMessage($data['auth_id'], $data['message'], $data['conversation_id'], $data['username'] );
        }
    }

    private function sendSupportChatMessage(int $senderId, string $message, string $title, ?int $ticketId): void
    {
        $message = Censure::replace($message);
        $ticketBackId = $ticketId;
        if ($ticketBackId === null) {
            $ticketBackId = $this->supportChatRepository->saveTicket($senderId, $title, $message);
        } else {
            $this->supportChatRepository->saveMessage($senderId, $ticketId, $message);
        }

        // Get the SupportTicket model using the ticket id
        $supportTicket = SupportTicket::find($ticketBackId);

        $messageObject = $this->messageFactory->createSupportChatMessage($senderId, $ticketBackId, $message);

        $moderators = Moderator::all()->pluck('user_id')->toArray();

        if (in_array($senderId, $moderators)) {
            if (isset($this->userConnections[$supportTicket->sender_id])) {
                $this->userConnections[$supportTicket->sender_id]->send(json_encode($messageObject));
            }
        } else {
            foreach($moderators as $moderatorId) {
                if (isset($this->userConnections[$moderatorId])) {
                    $this->userConnections[$moderatorId]->send(json_encode($messageObject));
                }
            }
        }
    }

    private function sendPersonalChatMessage(int $user_id, string $message, $conversation_id, string $username): void
    {
        $conversation = Conversation::findOrFail($conversation_id);

        $recipientId = ($conversation->user_one === $user_id) ? $conversation->user_two : $conversation->user_one;

        $message = Censure::replace($message);

        $messageObject = $this->messageFactory->createPersonalChatMessage($user_id, $message, $conversation_id, $username);

        $this->personalChatRepository->save($user_id, $conversation_id, $message);

        if (!isset($this->userConnections[$recipientId])) {
            return;
        }

        $this->userConnections[$recipientId]->send(json_encode($messageObject));
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
