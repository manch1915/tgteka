<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Notifications\WelcomeMessageNotification;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
        public function create(array $input): array
        {

            // Generate a random password
            $password = Str::random(10);

            // Create the user
            $user = User::create([
                'email' => $input['email'],
                'mobile_number' => $input['mobile_number'],
                'password' => Hash::make($password),
            ]);

            $user->notify(new WelcomeMessageNotification($user->email, $password));

            return [
                'user' => $user
            ];
        }
}
