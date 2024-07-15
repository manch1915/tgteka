<?php

namespace App\Http\Controllers\Admin;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function index()
    {
        $usersCount = User::count();
        $channelsCount = Channel::count();
        return inertia('Admin/Views/HomeView', [
            'channelsCount' => $channelsCount,
            'usersCount' => $usersCount,
        ]);
    }
}
