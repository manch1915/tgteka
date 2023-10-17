<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;

class TotalBalanceController extends Controller
{
    public function index()
    {
        return inertia('Dashboard/Profile/TotalBalance');
    }
}
