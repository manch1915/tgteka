<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function store(Request $request, CreateNewUser $creator)
    {
        $validatedData = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile_number' => ['required', 'string', 'min:16', 'max:16', 'unique:users', 'regex:/\+\d{1}\s\d{3}\s\d{3}-\d{2}-\d{2}/']
        ]);

        $creator->create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'User Registered Successfully'
        ], 200);
    }
}
