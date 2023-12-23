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
            ->content(sprintf("Ваш пост разместили, пожалуйста перейдите по ссылке и проверьте: [%s](%s)", $this->post_link, $this->post_link))
            ->options(['parse_mode' => 'Markdown']);
    }


    public function toArray($notifiable): array
    {
        return [];
    }
}
