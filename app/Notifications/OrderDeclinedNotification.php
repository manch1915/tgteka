<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class OrderDeclinedNotification extends Notification
{
    protected string $reason;

    public function __construct($reason)
    {
        $this->reason = $reason;
    }

    public function via($notifiable): array
    {
        return ['database', 'telegram', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Здравствуйте!')
            ->line("Ваш заказ был отменен по причине: " . $this->reason);
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => "Ваш заказ был отменен по причине: " . $this->reason,
        ];
    }

    public function toTelegram($notifiable): TelegramMessage
    {
        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->content("Ваш заказ был отменен по причине: " . $this->reason);
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
