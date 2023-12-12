<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class OrderAcceptedNotification extends Notification
{
    protected string $chanelTitle;

    public function __construct(string $chanelTitle)
    {
        $this->chanelTitle = $chanelTitle;
    }

    public function via($notifiable): array
    {
        return ['database', 'telegram', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Здравствуйте!')
            ->line("Ваш заказ приняли в канале: " . $this->chanelTitle);
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => "Ваш заказ приняли в канале: " . $this->chanelTitle,
        ];
    }

    public function toTelegram($notifiable): TelegramMessage
    {
        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->content("Ваш заказ приняли в канале: " . $this->chanelTitle);
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
