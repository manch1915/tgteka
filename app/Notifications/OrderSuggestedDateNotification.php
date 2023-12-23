<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class OrderSuggestedDateNotification extends Notification
{
    private string $suggestedDate;
    private int $suggestedPostDateId;
    private int $orderItemId;

    public function __construct(string $suggestedDate, int $suggestedPostDateId, int $orderItemId)
    {
        $this->suggestedDate = $suggestedDate;
        $this->suggestedPostDateId = $suggestedPostDateId;
        $this->orderItemId = $orderItemId;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail', 'telegram'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Здравствуйте!')
            ->line('Администратор канала предложил вам новую дату для размещения вашего поста: ' . $this->suggestedDate);
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => 'Администратор канала предложил вам новую дату для размещения вашего поста: ' . $this->suggestedDate,
        ];
    }

    /**
     * @throws \JsonException
     * @throws \Exception
     */
    public function toTelegram($notifiable): TelegramMessage
    {
        if (!$notifiable->telegram_user_id) {
            throw new \Exception("Вы должны войти в свою учетную запись Telegram, чтобы получить этот пост.");
        }

        $acceptUrl = route('suggested-date.accept', [$this->orderItemId, $this->suggestedDate]);
        $declineUrl = route('suggested-date.decline', $this->suggestedPostDateId);

        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->content('Администратор канала предложил вам новую дату для размещения вашего поста: ' . $this->suggestedDate)
            ->button('Принять', $acceptUrl)
            ->button('Отклонить', $declineUrl);
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
