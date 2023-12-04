<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use NotificationChannels\Telegram\TelegramServiceProvider;

class NotificationsSettingController extends Controller
{
    public function index()
    {
        return inertia('Dashboard/Profile/Notifications');
    }
}
