<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class OrderDeclinedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Order $order)
    {
    }

    public function via($notifiable): array
    {
        return ['database', 'mail', 'telegram'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Здравствуйте!')
            ->line('Ваш заказ #'.$this->order->id.' был отменен по причине: '.$this->order->decline_reason);
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => 'Ваш заказ #'.$this->order->id.' был отменен по причине: '.$this->order->decline_reason,
        ];
    }

    /**
     * @throws \Exception
     */
    public function toTelegram($notifiable): TelegramMessage
    {
        if (! $notifiable->telegram_user_id) {
            throw new \Exception('Вы должны войти в свою учетную запись Telegram, чтобы получить этот пост.');
        }

        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->content('Ваш заказ #'.$this->order->id.' был отменен по причине: '.$this->order->decline_reason);
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
