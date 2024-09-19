<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayoutFailedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected $transactionId)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Неудачная транзакция вывода')
            ->line('Ваша транзакция вывода с идентификатором '.$this->transactionId.' не была подтверждена в течение 10 минут и была помечена как неудачная.')
            ->line('Пожалуйста, попробуйте снова.');
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
