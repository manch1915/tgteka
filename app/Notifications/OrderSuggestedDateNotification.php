<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class OrderSuggestedDateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private string $suggestedDate, private int $suggestedPostDateId, private int $orderItemId, private string $channelName)
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
            ->line('Администратор канала предложил вам новую дату для размещения вашего поста на канале: '.$this->channelName.'. Заявка #'.$this->orderItemId.'. '.$this->suggestedDate);
    }

    public function toDatabase($notifiable): array
    {

        return [
            'message' => 'Администратор канала предложил вам новую дату для размещения вашего поста на канале: '.$this->channelName.'. Заявка #'.$this->orderItemId.'. '.$this->suggestedDate,
            'actions' => [
                [
                    'label' => 'Принять',
                    'route_name' => 'suggested-date.accept',
                    'parameters' => [$this->orderItemId, $this->suggestedDate],
                ],
                [
                    'label' => 'Отклонить',
                    'route_name' => 'suggested-date.decline',
                    'parameters' => [$this->suggestedPostDateId],
                ],
            ],
        ];
    }

    /**
     * @throws \JsonException
     * @throws \Exception
     */
    public function toTelegram($notifiable): TelegramMessage
    {
        if (! $notifiable->telegram_user_id) {
            throw new \Exception('Вы должны войти в свою учетную запись Telegram, чтобы получить этот пост.');
        }

        $acceptUrl = route('suggested-date.accept', [$this->orderItemId, $this->suggestedDate]);
        $declineUrl = route('suggested-date.decline', $this->suggestedPostDateId);

        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->content('Администратор канала предложил вам новую дату для размещения вашего поста на канале: '.$this->channelName.'. Заявка #'.$this->orderItemId.'. '.$this->suggestedDate)
            ->button('Принять', $acceptUrl)
            ->button('Отклонить', $declineUrl);
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
