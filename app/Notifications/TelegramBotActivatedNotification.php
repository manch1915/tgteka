<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramBotActivatedNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
    }

    public function via($notifiable): array
    {
        return ['mail', 'database', 'telegram'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Здравствуйте!')
            ->line('бот для уведомлений теперь активен!');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => 'бот для уведомлений теперь активен!',
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
            ->content("Здравствуйте, бот для уведомлений теперь активен!");
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
