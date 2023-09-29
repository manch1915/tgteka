<?php

namespace App\Console\Commands;

use App\Http\ChatWebSocketServer;
use App\Http\Repositories\ChatRepository;
use App\Http\Factories\MessageFactory;
use Illuminate\Console\Command;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class WebSocketServerCommand extends Command
{
    private const PORT = 8080;
    protected $signature = 'websocket:start';
    protected $description = 'Start the WebSocket Server';

    private $chatRepository;
    private $messageFactory;

    public function __construct(ChatRepository $chatRepository, MessageFactory $messageFactory)
    {
        parent::__construct();
        $this->chatRepository = $chatRepository;
        $this->messageFactory = $messageFactory;
    }

    public function handle(): void
    {
        $this->startServer($this->createChatServer());
    }

    private function createChatServer(): IoServer
    {
        return IoServer::factory(
            new HttpServer(
                new WsServer(
                    new ChatWebSocketServer($this->chatRepository, $this->messageFactory)
                )
            ),
            self::PORT
        );
    }

    private function startServer(IoServer $server): void
    {
        $server->run();
    }
}
