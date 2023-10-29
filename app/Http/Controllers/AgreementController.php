<?php

namespace App\Http\Controllers;

class AgreementController extends Controller
{
    public function index()
    {
        return inertia('Agreement');
    }
}
