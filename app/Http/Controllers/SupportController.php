<?php

namespace App\Http\Controllers;

class SupportController extends Controller
{
    public function index()
    {
        return inertia('Dashboard/Support');
    }
}
