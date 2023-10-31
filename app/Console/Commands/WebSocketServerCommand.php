<?php

namespace App\Console\Commands;

use App\Http\ChatWebSocketServer;
use App\Http\Factories\MessageFactory;
use App\Http\Repositories\PersonalChatRepository;
use App\Http\Repositories\SupportChatRepository;
use Illuminate\Console\Command;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class WebSocketServerCommand extends Command
{
    private const PORT = 8080;
    protected $signature = 'websocket:start';
    protected $description = 'Start the WebSocket Server';

    protected PersonalChatRepository $personalChatRepository;
    protected SupportChatRepository $supportChatRepository;
    protected MessageFactory $messageFactory;

    public function __construct(PersonalChatRepository $personalChatRepository, SupportChatRepository $supportChatRepository ,MessageFactory $messageFactory)
    {
        parent::__construct();
        $this->personalChatRepository = $personalChatRepository;
        $this->supportChatRepository = $supportChatRepository;
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
                    new ChatWebSocketServer($this->personalChatRepository, $this->supportChatRepository, $this->messageFactory)
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
