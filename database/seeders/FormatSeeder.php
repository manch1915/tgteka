<?php

namespace Database\Seeders;

use App\Models\Format;
use Illuminate\Database\Seeder;

class FormatSeeder extends Seeder
{
    public function run(): void
    {
        $formats = ['1/24', '2/48', '3/72', 'no_deletion', 'repost'];

        collect($formats)->each(function($format) {
            Format::create(['name' => $format]);
        });
    }
}
