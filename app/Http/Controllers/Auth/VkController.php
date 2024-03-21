<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class VkController extends Controller
{
    public function auth()
    {
        $vkUser = Socialite::driver('vkontakte')->user();

        $user = User::where('vk_id', $vkUser->id)->first();

        $username = strtolower(trim($vkUser->name));

        if (!$user) {
            if (User::where('username', $username)->exists()) {
                $username = $this->makeUniqueUsername($username);
            }

            $user = User::create([
                'vk_id' => $vkUser->id,
                'username' => $username,
            ]);
        }

        Auth::login($user);

        return redirect()->route('catalog.channels.index');
    }

    public function makeUniqueUsername($username)
    {
            // Add an underscore between words if the username already exists
            $username = str_replace(' ', '_', $username);

            // Append a unique number to the username
            $suffix = 1;
            $originalUsername = $username;
            while (User::where('username', $username)->exists()) {
                $username = $originalUsername . '_' . $suffix;
                $suffix++;
            }

            return $username;

    }
}
