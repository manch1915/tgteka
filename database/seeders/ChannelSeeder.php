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
            'https://t.me/granitnauky',
            'https://t.me/+B-qE5hUu4DBmMGY6',
            'https://t.me/cmd_cv',
            'https://t.me/+gFCT63NlnfE5YWZi'
        ];

        foreach ($urls as $index => $url) {
            $channel = Channel::factory()->create(['url' => $url, 'status' => 'loading']);
            FetchChannelStatisticsJob::dispatch($channel);
        }
    }
}
