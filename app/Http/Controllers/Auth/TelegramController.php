<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Notifications\TelegramBotActivatedNotification;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class TelegramController
{
    public function callback(Request $request)
    {
        $telegramUser = $request->all();

        if (auth()->check() && $telegramUser){
            auth()->user()->update([
                'telegram_username' => $telegramUser['username'],
                'telegram_user_id' => $telegramUser['id'],
            ]);
            $notification = new TelegramBotActivatedNotification();
            auth()->user()->notify($notification);
        }
        else {
            $user = User::where('telegram_user_id', $telegramUser['id'])->first();
            if ($user) {
                auth()->login($user);
                return response()->json('');
            } else {
                return response()->json('error', 402);
            }
        }

        return redirect()->route('notifications-setting')->with('Telegram connected successfully');
    }
}
