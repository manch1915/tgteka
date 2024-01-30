<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::create(['key' => 'replenishment_min_amount', 'value' => 500]);
    }
}
