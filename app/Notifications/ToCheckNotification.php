<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class ToCheckNotification extends Notification
{

    private string $post_link;

    public function __construct(string $post_link)
    {
        $this->post_link = $post_link;
    }

    public function via($notifiable): array
    {
        return ['database', 'telegram', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Здравствуйте!')
            ->line("Ваш пост разместили, пожалуйста перейдите по ссылке и проверьте: " . $this->post_link);
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => "Ваш пост разместили, пожалуйста перейдите по ссылке и проверьте: " . $this->post_link,
        ];
    }

    public function toTelegram($notifiable): TelegramMessage
    {
        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->content("Ваш пост разместили, пожалуйста перейдите по ссылке и проверьте: " . $this->post_link);
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
