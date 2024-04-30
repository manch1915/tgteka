<?php

namespace Database\Seeders;

use App\Jobs\FetchChannelStatisticsJob;
use App\Models\Channel;
use App\Models\Topic;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Str;

class ChannelSeeder extends Seeder
{
    public function run(): void
    {
        $topics = Topic::all();

        foreach ($topics as $topic) {

            for ($i = 0; $i < 6; $i++) {
                $uniqueUrl = '@' . Str::random(6);

                Channel::factory()->create([
                    'topic_id' => $topic->id,
                    'url' => $uniqueUrl,
                    'slug' => $uniqueUrl . '-' . $i,
                    'status' => 'accepted'
                ]);
            }
        }
    }
}
