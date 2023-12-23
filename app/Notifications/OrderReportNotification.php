<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class OrderReportNotification extends Notification
{
    protected string $report_text;

    public function __construct($report_text)
    {
        $this->report_text = $report_text;
    }

    public function via($notifiable): array
    {
        return ['database', 'telegram', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Здравствуйте!')
            ->line("На вас падали жалобу по поводу размещение поста на вашем канале. После проверки модераторами вы получите оповещение о решении. Текст жалобы: " . $this->report_text);
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => "На вас падали жалобу по поводу размещение поста на вашем канале. После проверки модераторами вы получите оповещение о решении. Текст жалобы: " . $this->report_text,
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
            ->content("На вас падали жалобу по поводу размещение поста на вашем канале. После проверки модераторами вы получите оповещение о решении. Текст жалобы: " . $this->report_text);
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
