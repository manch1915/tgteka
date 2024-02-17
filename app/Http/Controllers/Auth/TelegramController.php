<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Notifications\TelegramBotActivatedNotification;
use Laravel\Socialite\Facades\Socialite;

class TelegramController
{
    public function index()
    {
        return Socialite::driver('telegram')->redirect();
    }
    public function callback()
    {
        $telegramUser = Socialite::driver('telegram')->user();

        if (auth()->check() && $telegramUser){
            auth()->user()->update([
                'telegram_username' => $telegramUser->getNickname(),
                'telegram_user_id' => $telegramUser->getId(),
            ]);
            $notification = new TelegramBotActivatedNotification();
            auth()->user()->notify($notification);
        }
        else {
            $user = User::where('telegram_user_id', $telegramUser->getId())->first();
            if ($user) {
                auth()->login($user);
                return redirect()->route('catalog.channels.index');
            } else {
                return redirect()->route('owners');
            }
        }

        return redirect()->route('notifications-setting')->with('Telegram connected successfully');
    }
}
