<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class OrderToCheckNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private string $post_link, private Order $order)
    {
    }

    public function via($notifiable): array
    {
        return ['database', 'mail', 'telegram'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Заявка передана на проверку.')
            ->line('Здравствуйте!')
            ->line('Ваш пост разместили (Заявка #'.$this->order->id.'), пожалуйста перейдите по ссылке и проверьте: '.$this->post_link);
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'post_placed',
            'message' => 'Ваш пост разместили (Заявка #'.$this->order->id.'), перейдите по ссылке чтобы проверить.',
            'action_url' => $this->post_link,
            'action_label' => 'Перейти',
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
            ->content(sprintf('Ваш пост разместили (Заявка #'.$this->order->id.'), пожалуйста перейдите по ссылке и проверьте: [%s](%s)', $this->post_link, $this->post_link))
            ->options(['parse_mode' => 'Markdown']);
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
