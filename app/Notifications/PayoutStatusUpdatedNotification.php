<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class PayoutStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $payoutId;
    protected string $status;

    public function __construct(string $payoutId, string $status)
    {
        $this->payoutId = $payoutId;
        $this->status = $status;
    }

    public function via($notifiable): array
    {
        return ['database', 'telegram', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('У заявки на вывод ' . $this->payoutId . ' обновился статус')
            ->line('Уважаемый(ая) ' . $notifiable->username . ',')
            ->line('Мы рассмотрели вашу заявку на вывод и присвоили ей статус: ' . $this->status)
            ->line('Если у вас есть вопросы, вы можете написать нам в чат.')
            ->line('С уважением,')
            ->line('Команда 1-24.market');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => 'У заявки на вывод ' . $this->payoutId . ' обновился статус' ,
            'status' => $this->status,
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
            ->content('У заявки на вывод ' . $this->payoutId . ' обновился статус. Мы рассмотрели вашу заявку на вывод и присвоили ей статус: ' . $this->status);
    }
}
