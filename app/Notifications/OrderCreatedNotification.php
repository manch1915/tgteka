<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;
use App\Models\Order;

class OrderCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable): array
    {
        return ['database', 'telegram', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Новая заявка на размещение рекламы в вашем канале')
            ->line('Уважаемый(ая) ' . $notifiable->username . ',')
            ->line('Поступила новая заявка на размещение рекламы для канала ' . $this->order->channel->name . ':')
            ->line('- Стоимость заявки: ' . $this->order->price)
            ->line('- Формат размещения: ' . $this->order->format->name)
            ->line('- Комментарий: ' . $this->order->description)
            ->line('- ID заявки: ' . $this->order->id)
            ->line('Пожалуйста, проверьте и обработайте данную заявку.')
            ->action('Посмотреть заявку', route('order.index'))
            ->line('С уважением,')
            ->line('Команда 1-24.market');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => 'Поступила новая заявка на размещение рекламы для канала ' . $this->order->channel->name,
        ];
    }

    public function toTelegram($notifiable)
    {
        if (!$notifiable->telegram_user_id) {
            throw new \Exception("Пожалуйста, войдите в Telegram, чтобы получить уведомление.");
        }

        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->content('Поступила новая заявка на размещение рекламы для канала ' . $this->order->channel->name . '. Проверьте и обработайте данную заявку.');
    }

}
