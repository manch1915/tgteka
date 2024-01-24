<?php

namespace Database\Seeders;

use App\Models\Moderator;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $created_user = User::create(['email' => 'admin@gmail.com', 'username' => 'admin', 'password' => Hash::make('admin'), 'balance' => 15000]);
        $created_user->assignRole('Admin');
        Moderator::create(['user_id' => $created_user['id']]);
    }
}
