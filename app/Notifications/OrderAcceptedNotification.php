<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class OrderAcceptedNotification extends Notification implements ShouldQueue
{
    use Queueable;

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
            ->subject('Ваш заказ приняли!')
            ->line('Здравствуйте!')
            ->line("Ваш заказ приняли в канале: " . $this->chanelTitle);
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => "Ваш заказ приняли в канале: " . $this->chanelTitle,
        ];
    }

    /**
     * @throws \Exception
     */
    public function toTelegram($notifiable)
    {
        if (!$notifiable->telegram_user_id) {
            throw new \Exception("Вы должны войти в свою учетную запись Telegram, чтобы получить этот пост.");
        }

        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->content("Ваш заказ приняли в канале: " . $this->chanelTitle);
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
