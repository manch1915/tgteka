<?php

namespace App\Notifications;

use App\Models\Pattern;
use App\Services\AvatarService;
use App\Services\HtmlTelegramFile;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramFile;
use NotificationChannels\Telegram\TelegramMessage;

class PatternByBotNotification extends Notification
{

    protected Pattern $pattern;
    protected AvatarService $avatarService;

    public function __construct(Pattern $pattern, AvatarService $avatarService)
    {
        $this->avatarService = $avatarService;
        $this->pattern = $pattern;
    }

    public function via($notifiable): array
    {
        return ['telegram'];
    }

    public function reformatTelegramHTML($text): string
    {
        // Allowed HTML tags
        $allowedTags = '<b><strong><i><em><a><code><pre>';

        // Use PHP's built-in strip_tags function
        // to remove all tags except the allowed ones
        $text = strip_tags($text, $allowedTags);

        return $text;
    }

    public function toTelegram($notifiable): TelegramFile
    {
        $avatarUrl = $this->avatarService->getAvatarUrlOfPattern($this->pattern);
        $path = parse_url($avatarUrl, PHP_URL_PATH);
        $path = ltrim($path, "/");
        $content = $this->pattern->body;
        $content = $this->reformatTelegramHTML($content);

        $telegramMessage = TelegramFile::create()
            ->to($notifiable->telegram_user_id)
            ->content($content)
        ->options(['parse_mode' => 'HTML']);


        if ($avatarUrl !== null) {
            $telegramMessage->file($path, 'photo'); // local photo
        }

        return $telegramMessage;
    }
}
