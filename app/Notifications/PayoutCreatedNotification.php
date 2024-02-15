<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class PayoutCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $payoutId;

    public function __construct(string $payoutId)
    {
        $this->payoutId = $payoutId;

    }

    public function via($notifiable): array
    {
        return ['database', 'mail', 'telegram'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Заявка на вывод средств ' . $this->payoutId . ' создана')
            ->line('Уважаемый(ая) ' . $notifiable->username . ',')
            ->line('Вы создали заявку ' . $this->payoutId . ' на вывод средств. Мы рассмотрим ваш запрос в ближайшее время.')
            ->line('С уважением,')
            ->line('Команда 1-24.market');

    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => 'Вы создали заявку ' . $this->payoutId . ' на вывод средств' ,
        ];
    }

    /**
     * @throws \Exception
     */
    public function toTelegram($notifiable)
    {
        if (!$notifiable->telegram_user_id) {
            throw new \Exception("Вы должны войти в свою учетную запись Telegram, чтобы получить мне сообщение.");
        }

        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->content('Вы создали заявку на вывод ' . $this->payoutId . '. Мы рассмотрим ваш запрос в ближайшее время.');
    }

}
