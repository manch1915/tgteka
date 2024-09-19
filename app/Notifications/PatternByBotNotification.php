<?php

namespace App\Notifications;

use App\Models\Pattern;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

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

    private function stripHtmlAttributes($text): string
    {
        return preg_replace('/\s*(rel|target)="[^"]*"/i', '', $text);
    }

    private function reformatTags($text): string
    {
        $text = preg_replace_callback('/<a\s+(.*?)>(.*?)<\/a>/si', function ($match) {
            $hrefAttribute = $match[1] ?? '';

            return '<a '.$hrefAttribute.'>'.strip_tags($match[2]).'</a>';
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
        if (! $notifiable->telegram_user_id) {
            throw new \Exception('Вы должны войти в свою учетную запись Telegram, чтобы получить этот пост.');
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
                    'thumbnail_path' => $item->getCustomProperty('thumbnail_path'),
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
        $bot_token = config('services.telegram-bot-api.token');
        $url = "/bot{$bot_token}/sendMediaGroup";

        $response = $client->post($url, [
            'multipart' => [
                [
                    'name' => 'chat_id',
                    'contents' => $chatId,
                ],
                [
                    'name' => 'media',
                    'contents' => json_encode($mediaGroup),
                ],
            ],
        ]);

        return $response;
    }

    public function endsWith($haystack, $needle): bool
    {
        return substr_compare($haystack, $needle, -strlen($needle)) === 0;
    }
}
