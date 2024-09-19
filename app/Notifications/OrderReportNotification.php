<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class OrderReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected string $report_text, protected Order $order)
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
            ->line('На вас падали жалобу по поводу размещение поста на вашем канале. После проверки модераторами вы получите оповещение о решении. Текст жалобы: '.$this->report_text.'. Заявка: #'.$this->order->id);
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => 'На вас падали жалобу по поводу размещение поста на вашем канале. После проверки модераторами вы получите оповещение о решении. Текст жалобы: '.$this->report_text.'. Заявка: #'.$this->order->id,
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
            ->content('На вас падали жалобу по поводу размещение поста на вашем канале. После проверки модераторами вы получите оповещение о решении. Текст жалобы: '.$this->report_text.'. Заявка: #'.$this->order->id);
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
