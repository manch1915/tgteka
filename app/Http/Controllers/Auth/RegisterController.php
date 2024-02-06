<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function create(Request $request, CreateNewUser $creator)
    {
        $validatedData = $request->validate([
            'username' => ['required', 'string', 'min:5', 'max:16', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
        ]);

        $creator->create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'User Registered Successfully'
        ], 200);
    }
}
