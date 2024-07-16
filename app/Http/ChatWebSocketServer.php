<?php

namespace App\Http;

use App\Http\Factories\MessageFactory;
use App\Http\Repositories\PersonalChatRepository;
use App\Http\Repositories\SupportChatRepository;
use App\Services\WebSocketMessageProviders\WebSocketMessageProvider;
use Exception;
use Illuminate\Support\Facades\Log;
use SplObjectStorage;
use Workerman\Connection\TcpConnection;

class ChatWebSocketServer
{
    protected SplObjectStorage $clients;
    protected array $userConnections;
    protected array $userMainConnections;
    protected PersonalChatRepository $personalChatRepository;
    protected SupportChatRepository $supportChatRepository;
    protected MessageFactory $messageFactory;

    public function __construct(
        PersonalChatRepository $personalChatRepository,
        SupportChatRepository $supportChatRepository,
        MessageFactory $messageFactory
    ) {
        $this->clients = new SplObjectStorage;
        $this->userConnections = [];
        $this->personalChatRepository = $personalChatRepository;
        $this->supportChatRepository = $supportChatRepository;
        $this->messageFactory = $messageFactory;
    }

    public function onOpen(TcpConnection $connection): void
    {
        $this->clients->attach($connection);
        $clientId = spl_object_hash($connection);
        echo "New connection! ({$clientId})\n";

        // Use the query parameters stored in the connection object
        $queryArray = $connection->queryParams ?? [];

        $userId = $queryArray['userid'] ?? null;
        if ($userId !== null) {
            if (!isset($this->userMainConnections[$userId])) {
                $this->userMainConnections[$userId] = $connection;
            } else {
                $this->userConnections[$userId] = $connection;
            }
        }
    }

    public function onMessage(TcpConnection $connection, $data): void
    {
        $data = json_decode($data, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("JSON decode error: " . json_last_error_msg());
        } else {
            $this->handleChatMessage($data);
        }
    }

    public function onClose(TcpConnection $connection): void
    {
        $this->clients->detach($connection);

        $userId = array_search($connection, $this->userConnections);
        if ($userId !== false) {
            unset($this->userConnections[$userId]);
        }

        $clientId = spl_object_hash($connection);
        Log::info("Connection {$userId} has disconnected");
    }

    public function onError(TcpConnection $connection, \Exception $e): void
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $connection->close();
    }

    private function handleChatMessage(array $data): void
    {
        $type = $data['type'] ?? 'chat';
        try {
            $messageProvider = WebSocketMessageProvider::factory(
                $type,
                $this->personalChatRepository,
                $this->supportChatRepository,
                $this->messageFactory
            );
            $messageProvider->sendMessage($data, $this->userConnections, $this->userMainConnections);
        } catch (Exception $e) {
            error_log("WebSocketMessageProvider error: " . $e->getMessage());
        }
    }
}
