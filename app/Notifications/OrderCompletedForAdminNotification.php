<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class OrderCompletedForAdminNotification extends Notification
{
    protected string $price;
    protected string $channel;

    public function __construct($price, $channel)
    {
        $this->channel = $channel;
        $this->price = $price;
    }

    public function via($notifiable): array
    {
        return ['database', 'telegram', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Здравствуйте!')
            ->line("Вы успешно выполнили свой заказ на канале" . $this->channel)
            ->line($this->price . "₽ был переведён на ваш счёт");
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => "Вы успешно выполнили свой заказ на канале: " . $this->channel . '. ' . $this->price . "₽ был переведён на ваш счёт",
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
            ->content("Вы успешно выполнили свой заказ на канале" . $this->channel)
            ->content($this->price . "₽ был переведён на ваш счёт");
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
