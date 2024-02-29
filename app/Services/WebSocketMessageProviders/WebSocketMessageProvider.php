<?php
namespace App\Services\WebSocketMessageProviders;

use App\Http\Factories\MessageFactory;
use App\Http\Repositories\PersonalChatRepository;
use App\Http\Repositories\SupportChatRepository;
use InvalidArgumentException;

abstract class WebSocketMessageProvider {
    protected PersonalChatRepository $personalChatRepository;
    protected SupportChatRepository $supportChatRepository;
    protected MessageFactory $messageFactory;

    // Notice the constructor of abstract class is taking necessary tools to preform it's operations
    public function __construct(PersonalChatRepository $personalChatRepository, SupportChatRepository $supportChatRepository ,MessageFactory $messageFactory){
        $this->personalChatRepository = $personalChatRepository;
        $this->supportChatRepository = $supportChatRepository;
        $this->messageFactory = $messageFactory;
        logger('WebSocketMessageProvider constructed');
    }

    abstract public function sendMessage(array $data, array $userConnections): void;

    public static function factory(string $type, PersonalChatRepository $personalChatRepository,
                                   SupportChatRepository $supportChatRepository,
                                   MessageFactory $messageFactory): NewMessageHandler|NewSupportMessageHandler|NewNotificationHandler
    {
        logger($type);
        return match ($type) {
            "chat_message" => new NewMessageHandler($personalChatRepository, $supportChatRepository, $messageFactory),
            "support_message" => new NewSupportMessageHandler($personalChatRepository, $supportChatRepository, $messageFactory),
            "notification" => new NewNotificationHandler($personalChatRepository, $supportChatRepository, $messageFactory),
            default => throw new InvalidArgumentException("Invalid provider type specified"),
        };
    }
}
