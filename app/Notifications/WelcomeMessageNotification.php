<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class WelcomeMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private string $userName;
    private string $password;

    public function __construct(string $userName, string $password)
    {
        $this->userName = $userName;
        $this->password = $password;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database', 'telegram'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'userName' => $this->userName,
            'subject' => "Добро пожаловать на 1-24.market, $this->userName!",
            'message' => "Приветствуем вас на 1-24.market, $this->userName! Благодарим за регистрацию на нашей платформе. Теперь у вас есть возможность покупать и продавать рекламу в Telegram-каналах. С наилучшими пожеланиями, Команда 1-24.market"
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Добро пожаловать на 1-24.market, $this->userName!")
            ->line("Приветствуем вас на 1-24.market, $this->userName!")
            ->line("Благодарим за регистрацию на нашей платформе. Ваш пароль: {$this->password}. Теперь у вас есть возможность покупать и продавать рекламу в Telegram-каналах.")
            ->action('Перейти на биржу рекламы', route('catalog.channels.index'))
            ->line('С наилучшими пожеланиями,')
            ->line('Команда 1-24.market');
    }

    /**
     * @throws \Exception
     */
    public function toTelegram($notifiable): TelegramMessage
    {
        if (!$notifiable->telegram_user_id) {
            throw new \Exception("Вы должны войти в свою учетную запись Telegram, чтобы получить этот пост.");
        }
        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->content(sprintf(
                "Приветствуем вас на 1-24.market, %s! Благодарим за регистрацию на нашей платформе. Теперь у вас есть возможность покупать и продавать рекламу в Telegram-каналах.\nС наилучшими пожеланиями, Команда 1-24.market",
                $this->userName))
            ->button('Перейти на биржу рекламы', route('catalog.channels.index'));
    }
}
