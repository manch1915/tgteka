<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
        return response()->json('success');
    }

    public function generate(Request $request)
    {
        $password = Str::random(10);
        auth()->user()->update(['password' => Hash::make($password)]);
        $user = auth()->user();
        Mail::raw("Вот ваш новый сгенерированный: {$password}", function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Добро пожаловать в наше приложение');
        });

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
