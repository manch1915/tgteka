<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\ChannelStatistic;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ChannelStatisticsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create();

        // Get all channels
        $channels = Channel::all();

        foreach ($channels as $channel) {
            // Generate JSON statistics
            $statistics = [
                'id' => $faker->randomNumber(8),
                'title' => $faker->word,
                'ci_index' => null,
                'username' => '@' . $faker->userName,
                'peer_type' => 'channel',
                'er_percent' => null,
                'daily_reach' => $faker->randomNumber(5),
                'err_percent' => $faker->randomFloat(2, 0, 100),
                'posts_count' => $faker->numberBetween(1, 100),
                'err24_percent' => $faker->randomFloat(2, 0, 100),
                'avg_post_reach' => $faker->randomNumber(5),
                'forwards_count' => $faker->numberBetween(1, 100),
                'mentions_count' => $faker->numberBetween(1, 100),
                'adv_post_reach_12h' => null,
                'adv_post_reach_24h' => $faker->randomNumber(5),
                'adv_post_reach_48h' => null,
                'participants_count' => $faker->numberBetween(1, 100),
                'mentioning_channels_count' => $faker->numberBetween(1, 100),
            ];

            // Generate JSON data for other fields (arrays of objects)
            $periods = [];
            $avgPostsReach = [];
            $erValues = [];

            for ($i = 0; $i < 22; $i++) {
                $periods[] = [
                    'period' => $faker->dateTimeThisMonth()->format('Y-m-d'),
                    'participants_count' => $faker->numberBetween(100, 2000),
                ];

                $avgPostsReach[] = [
                    'period' => $faker->dateTimeThisMonth()->format('Y-m-d'),
                    'avg_posts_reach' => $faker->numberBetween(5000, 10000),
                ];

                $erValues[] = [
                    'er' => $faker->randomFloat(1, 0, 100),
                    'period' => $faker->dateTimeThisMonth()->format('Y-m-d'),
                ];
            }

            $statisticsJson = json_encode($statistics);
            $periodsJson = json_encode($periods);
            $avgPostsReachJson = json_encode($avgPostsReach);
            $erValuesJson = json_encode($erValues);

            // Create ChannelStatistic record
            ChannelStatistic::create([
                'channel_id' => $channel->id,
                'stats' => $statisticsJson,
                'subscribers' => $periodsJson,
                'avg_posts_reach' => $avgPostsReachJson,
                'er' => $erValuesJson,
            ]);
        }
    }
}
