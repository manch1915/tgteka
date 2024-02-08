<?php

namespace App\Notifications;

use App\Models\Pattern;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use NotificationChannels\Telegram\TelegramFile;
use NotificationChannels\Telegram\TelegramMessage;
use GuzzleHttp\Client;

class PatternByBotNotification extends Notification
{

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
     * @throws GuzzleException
     */
    public function toTelegram($notifiable)
    {
        if (!$notifiable->telegram_user_id) {
            throw new \Exception("Вы должны войти в свою учетную запись Telegram, чтобы получить этот пост.");
        }

        $content = $this->pattern->body;
        $content = $this->reformatTags($content);
        $content = $this->stripHtmlAttributes($content);

        $patternMedia = $this->pattern
            ->getMedia('images')
            ->map(function ($item) {
                return [
                    'url' => $item->getUrl(),
                    'order' => $item->getCustomProperty('order'),
                    'thumbnail_path' => $item->getCustomProperty('thumbnail_path')
                ];
            });

        $mediaGroup = [];

        foreach ($patternMedia as $media) {
            $type = $this->endsWith($media['url'], '.mp4') ? 'video' : 'photo';

            $mediaGroup[] = [
                'type' => $type,
                'media' => $media['url'],
            ];
        }
        $this->sendMediaGroupToTelegram($mediaGroup, $notifiable->telegram_user_id);

        $telegramMessage = TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->content($content)
            ->options(['parse_mode' => 'HTML']);

        return $telegramMessage;
    }


    /**
     * @throws GuzzleException
     */
    public function sendMediaGroupToTelegram($mediaGroup, $chatId)
    {
        $client = new Client(['base_uri' => 'https://api.telegram.org']);

        $response = $client->post('/bot6373670843:AAHG1bxOiFTKfRHWq24TiHzyVpQ45a7UxUc/sendMediaGroup', [
            'multipart' => [
                [
                    'name' => 'chat_id',
                    'contents' => $chatId
                ],
                [
                    'name' => 'media',
                    'contents' => json_encode($mediaGroup)
                ]
            ]
        ]);

        return $response;
    }

    function endsWith($haystack, $needle): bool
    {
        return substr_compare($haystack, $needle, -strlen($needle)) === 0;
    }
}
