<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class ChannelReviewNotification extends Notification
{
    protected string $reviewText;
    protected string $channelRating;
    protected string $channelName;

    public function __construct($reviewText, $channelRating, $channelName)
    {
        $this->reviewText = $reviewText;
        $this->channelRating = $channelRating;
        $this->channelName = $channelName;
    }

    public function via($notifiable): array
    {
        return ['database', 'telegram', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Здравствуйте!')
            ->line("У вас новый отзыв для канала: $this->channelName, Текст отзыва: $this->reviewText, $this->channelRating звёзд.");
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => "У вас новый отзыв для канала: $this->channelName, Текст отзыва: $this->reviewText, $this->channelRating звёзд.",
        ];
    }

    /**
     * @throws \Exception
     */
    public function toTelegram($notifiable): TelegramMessage
    {
        if (!$notifiable->telegram_user_id) {
            throw new \Exception("Вы должны войти в свою учетную запись Telegram, чтобы получить этот пост.");
        }
        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->content("У вас новый отзыв для канала: $this->channelName, Текст отзыва: $this->reviewText, $this->channelRating звёзд.");
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
