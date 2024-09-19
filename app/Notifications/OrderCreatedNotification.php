<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class OrderCreatedNotification extends Notification implements ShouldQueue
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
        $mailMessage = (new MailMessage)
            ->subject('Новая заявка на размещение рекламы в вашем канале')
            ->line('Уважаемый(ая) '.$notifiable->username.',')
            ->line('Поступила новая заявка на размещение рекламы для канала '.$this->order->channel->channel_name.':')
            ->line('- Стоимость заявки: '.$this->order->price)
            ->line('- Формат размещения: '.$this->order->format->name);

        if ($this->order->description) {
            $mailMessage->line('- Комментарий: '.$this->order->description);
        } else {
            $mailMessage->line('- Комментарий: отсутствует');
        }

        $mailMessage->line('- ID заявки: '.$this->order->id)
            ->line('Пожалуйста, проверьте и обработайте данную заявку.')
            ->action('Посмотреть заявку', route('order.index'))
            ->line('С уважением,')
            ->line('Команда 1-24.market');

        return $mailMessage;
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => 'Поступила новая заявка на размещение рекламы для канала '.$this->order->channel->channel_name,
            'action_url' => route('order.index'),
            'action_label' => 'Перейти',
        ];
    }

    public function toTelegram($notifiable)
    {
        if (! $notifiable->telegram_user_id) {
            throw new \Exception('Пожалуйста, войдите в Telegram, чтобы получить уведомление.');
        }

        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->content('Поступила новая заявка на размещение рекламы для канала '.$this->order->channel->channel_name.'. Проверьте и обработайте данную заявку.');
    }
}
