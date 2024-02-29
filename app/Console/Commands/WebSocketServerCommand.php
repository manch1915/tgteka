<?php

namespace App\Console\Commands;

use App\Http\ChatWebSocketServer;
use App\Http\Factories\MessageFactory;
use App\Http\Repositories\PersonalChatRepository;
use App\Http\Repositories\SupportChatRepository;
use Illuminate\Console\Command;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Loop;
use React\Socket\SecureServer;
use React\Socket\SocketServer;

class WebSocketServerCommand extends Command
{
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
        $loop   = Loop::get();

        $webSock = new SocketServer('0.0.0.0:1915',[], $loop);

        $webSock = new SecureServer(
            $webSock,
            $loop,
            array(
                'local_cert'        => config('websockets.ssl.local_cert'),
                'local_pk'          => config('websockets.ssl.local_pk'),
                'allow_self_signed' => config('websockets.ssl.allow_self_signed'),
                'verify_peer' => config('websockets.ssl.verify_peer')
            )
        );

        $webServer = new HttpServer(new WsServer(
            new ChatWebSocketServer($this->personalChatRepository, $this->supportChatRepository, $this->messageFactory)
        ));

        return new IoServer($webServer, $webSock, $loop);
    }

    private function startServer(IoServer $server): void
    {
        $server->run();
    }
}
