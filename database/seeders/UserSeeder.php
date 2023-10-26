<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create(['email' => 'admin@gmail.com', 'name' => 'admin', 'password' => Hash::make('admin')]);
    }
}
