<?php

namespace App\Notifications;

use App\Models\Pattern;
use App\Services\AvatarService;
use App\Services\HtmlTelegramFile;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
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

    private function stripHtmlAttributes($text): string {
        return preg_replace('/\s*(rel|target)="[^"]*"/i', '', $text);
    }

    private function reformatTags($text): string {
        $text = preg_replace_callback('/<a\s+(.*?)>(.*?)<\/a>/si', function($match) {
            $hrefAttribute = $match[1] ?? '';
            return '<a ' . $hrefAttribute . '>' . strip_tags($match[2]) . '</a>';
        }, $text);

        $allowedTags = '<b><strong><i><em><u><a><code><pre>';
        return strip_tags($text, $allowedTags);
    }

    public function toTelegram($notifiable): TelegramFile {
        $avatarUrl = $this->avatarService->getAvatarUrlOfPattern($this->pattern);
        $path = parse_url($avatarUrl, PHP_URL_PATH);
        $path = ltrim($path, "/");
        $content = $this->pattern->body;

        $content = $this->reformatTags($content);
        $content = $this->stripHtmlAttributes($content);

        Log::info($content);

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
