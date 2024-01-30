<?php

namespace Database\Seeders;

use App\Jobs\FetchChannelStatisticsJob;
use App\Models\Channel;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    public function run(): void
    {
        $urls = [
            'https://t.me/phpyhtelka',
            'https://t.me/granitnauky',
            'https://t.me/+B-qE5hUu4DBmMGY6'
        ];

        foreach ($urls as $url) {
            $channel = Channel::factory()->create(['url' => $url]);
            FetchChannelStatisticsJob::dispatch($channel);
        }
    }
}
