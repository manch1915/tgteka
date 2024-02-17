<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendTwoFactorCodeNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public function __construct()
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('1-24.market двухфакторная аутентификация')
            ->line('Приветствуем Вас, ' . $notifiable->username . '!')
            ->line('Вы получили это сообщение потому что произошла попытка входа в ваш аккаунт на 1-24.market.')
            ->line('Ваш код двухфакторной аутентификации : ' . $notifiable->two_factor_code)
            ->line('Данный код будет действителен в течение 10 минут.')
            ->line('Если вы не делали подобных запросов, пожалуйста, свяжитесь с нашей службой поддержки.')
            ->action('Войдите в аккаунт', route('customers'))
            ->line('С наилучшими пожеланиями,')
            ->line('Команда 1-24.market');
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
