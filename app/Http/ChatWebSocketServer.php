<?php

namespace App\Http;

use App\Http\Factories\MessageFactory;
use App\Http\Repositories\PersonalChatRepository;
use App\Http\Repositories\SupportChatRepository;
use App\Models\Conversation;
use App\Models\Moderator;
use App\Models\SupportTicket;
use App\Services\Censure;
use App\Services\WebSocketMessageProviders\WebSocketMessageProvider;
use Exception;
use Illuminate\Support\Facades\Log;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

class ChatWebSocketServer implements MessageComponentInterface
{
    protected SplObjectStorage $clients;
    protected array $userConnections;
    protected array $userMainConnections;
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
            // Save the main connection for the user if it's not already set
            if (!isset($this->userMainConnections[$userId])) {
                $this->userMainConnections[$userId] = $conn;
            }else{
                $this->userConnections[$userId] = $conn;
            }

            // Save the current connection as a user connection
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
        try {
            $messageProvider = WebSocketMessageProvider::factory($type, $this->personalChatRepository, $this->supportChatRepository, $this->messageFactory);
            $messageProvider->sendMessage($data, $this->userConnections, $this->userMainConnections);
        } catch (Exception $e) {
            error_log("WebSocketMessageProvider error: " . $e->getMessage());
        }
    }

}
