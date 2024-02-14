<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationsHistoryController extends Controller
{
    public function index()
    {
        return inertia('Dashboard/Profile/NotificationsHistory');
    }

    public function getNotifications(Request $request)
    {
        $order = $request->input('order', 'all');
        $sort = $request->input('sort', 'desc');

        if ($order === 'read') {
            $notifications = auth()->user()->readNotifications();
        } elseif ($order === 'unread') {
            $notifications = auth()->user()->unreadNotifications();
        }else{
            $notifications = auth()->user()->notifications();
        }

        $notifications = $notifications->orderBy('created_at', 'desc');
        $notifications = $notifications->paginate(10);
        return response()->json($notifications);
    }

    public function markAsReadAll()
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function unreadNotificationsCount()
    {
        return response()->json(auth()->user()->unreadNotifications()->count());
    }
}
