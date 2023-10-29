<?php

namespace Database\Seeders;

use App\Models\Pattern;
use Illuminate\Database\Seeder;

class PatternSeeder extends Seeder
{
    public function run(): void
    {
        Pattern::factory(150)->create();
    }
}
