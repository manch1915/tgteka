<?php

namespace Database\Seeders;

use App\Models\Format;
use Illuminate\Database\Seeder;

class FormatSeeder extends Seeder
{
    public function run(): void
    {
        $formats = [
            ['name' => '1/24'],
            ['name' => '2/48'],
            ['name' => '3/72'],
            ['name' => 'no_deletion'],
            ['name' => 'repost']];

        Format::insert($formats);
    }
}
