<?php

namespace App\Notifications;

use App\Models\Pattern;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\File;
use NotificationChannels\Telegram\TelegramFile;
use NotificationChannels\Telegram\TelegramMessage;

class PatternByBotNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Pattern $pattern;

    public function __construct(Pattern $pattern)
    {
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
    public function toTelegram($notifiable): TelegramMessage {
        if (!$notifiable->telegram_user_id) {
            throw new \Exception("Вы должны войти в свою учетную запись Telegram, чтобы получить этот пост.");
        }
        //todo otpravka patternov cherez bota
        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->content('asdsad')
            ->options(['parse_mode' => 'HTML']);
    }
}
