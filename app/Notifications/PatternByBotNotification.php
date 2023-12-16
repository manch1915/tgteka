<?php

namespace App\Notifications;

use App\Models\Pattern;
use App\Services\AvatarService;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\File;
use NotificationChannels\Telegram\TelegramFile;

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

    /**
     * @throws \Exception
     */
    public function toTelegram($notifiable): TelegramFile {
        if (!$notifiable->telegram_user_id) {
            throw new \Exception("Вы должны войти в свою учетную запись Telegram, чтобы получить этот пост.");
        }

        $avatarUrl = $this->avatarService->getAvatarUrlOfPattern($this->pattern);
        $path = parse_url($avatarUrl, PHP_URL_PATH);
        $path = ltrim($path, "/");
        $content = $this->pattern->body;

        $content = $this->reformatTags($content);
        $content = $this->stripHtmlAttributes($content);

        $telegramMessage = TelegramFile::create()
            ->to($notifiable->telegram_user_id)
            ->content($content)
            ->options(['parse_mode' => 'HTML']);

        if ($avatarUrl !== null) {
            $telegramMessage->file($path, 'photo');
        }else{
            $defaultImagePath = 'images/photo.png';

            if (File::exists(public_path($defaultImagePath))) {
                $telegramMessage->file(public_path($defaultImagePath), 'photo');
            } else {
                throw new \Exception("Default image not found: ");
            }
        }

        return $telegramMessage;
    }
}
