<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;

class ReplenishmentController extends Controller
{
    public function index()
    {
        return inertia('Dashboard/Profile/Replenishment');
    }
}
