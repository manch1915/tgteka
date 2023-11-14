<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    public function index()
    {
        return inertia('Dashboard/Profile/Withdraw');
    }
}
