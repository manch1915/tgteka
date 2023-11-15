<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function index()
    {
        return inertia('Dashboard/Profile/ChangePassword');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required','max:25', 'min:8','confirmed'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        auth()->user()->update($validated);
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
