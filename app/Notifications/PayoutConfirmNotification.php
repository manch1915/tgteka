<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class PayoutConfirmNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected string $confirmLink, protected string $amount)
    {
    }

    public function via($notifiable): array
    {
        return ['mail', 'telegram'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Требуется ваше подтверждение вывода средств.')
            ->line("Уважаемый(ая) $notifiable->username")
            ->line('Для создания заявки на вывод средств, перейдите по ссылке ниже.')
            ->line("Сумма вывода: $this->amount")
            ->action('Ссылка для подтверждения', $this->confirmLink);
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
            ->content(
                "Уважаемый(ая)  <b>$notifiable->username</b> , \n".
                'Для создания заявки на вывод средств, перейдите по ссылке ниже.'.
                "<b>Сумма вывода: </b>  $this->amount \n".
                "<a href='".$this->confirmLink."'>Ссылка для подтверждения</a> "
            )
            ->button('Ссылка для подтверждения', $this->confirmLink)
            ->options(['parse_mode' => 'HTML']);
    }
}
