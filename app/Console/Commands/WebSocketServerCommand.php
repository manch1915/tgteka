<?php

namespace App\Console\Commands;

use App\Http\ChatWebSocketServer;
use App\Http\Factories\MessageFactory;
use App\Http\Repositories\PersonalChatRepository;
use App\Http\Repositories\SupportChatRepository;
use Illuminate\Console\Command;
use Workerman\Worker;

class WebSocketServerCommand extends Command
{
    protected $signature = 'websocket:start {mode?} {--d|daemon : Run the WebSocket server in daemon mode}';

    protected $description = 'Start the WebSocket Server';

    public function __construct(
        protected PersonalChatRepository $personalChatRepository,
        protected SupportChatRepository $supportChatRepository,
        protected MessageFactory $messageFactory,
        protected ChatWebSocketServer $chatWebSocketServer,
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $mode = $this->argument('mode') ?: 'start';
        $daemon = $this->option('daemon');
        $this->startServer();
    }

    private function startServer(): void
    {
        // SSL context
        $context = [
            'ssl' => [
                'local_cert' => base_path(config('websockets.ssl.local_cert')),
                'local_pk' => base_path(config('websockets.ssl.local_pk')),
                'verify_peer' => config(('websockets.ssl.verify_peer')),
            ],
        ];

        // Create a WebSocket server with SSL context
        $ws_worker = new Worker('websocket://0.0.0.0:1915', $context);
        $ws_worker->transport = 'ssl';

        // Set up the WebSocket server
        $ws_worker->onWebSocketConnect = function ($connection, $header) {
            $connection->queryParams = $_GET;
            $this->chatWebSocketServer->onOpen($connection);
        };
        $ws_worker->onMessage = function ($connection, $data) {
            $this->chatWebSocketServer->onMessage($connection, $data);
        };
        $ws_worker->onClose = function ($connection) {
            $this->chatWebSocketServer->onClose($connection);
        };
        $ws_worker->onError = function ($connection, $code, $msg) {
            $this->chatWebSocketServer->onError($connection, new \Exception($msg));
        };

        // Start the worker
        Worker::runAll();
    }
}
