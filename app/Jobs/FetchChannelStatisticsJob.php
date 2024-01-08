<?php

namespace App\Jobs;

use App\Models\Channel;
use App\Models\ChannelStatistic;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FetchChannelStatisticsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Channel $channel;

    public function __construct(Channel $channel)
    {
        $this->channel = $channel;
    }

    /**
     * @throws GuzzleException
     */
    public function handle(): void
    {

        $client = new Client();

        $baseURL = 'https://api.tgstat.ru/';

        $token = '3de0a70af1d9e03800a888742cf07468'; //token

        $channel = $this->channel;

        $generalStatisticsResponse = $client->request('GET', $baseURL.'channels/stat', ['query' => ['token' => $token, 'channelId' => $channel->url]]);
        $generalStatistics = json_decode($generalStatisticsResponse->getBody()->getContents());

        if ($generalStatistics->status === "error") {

            $client->request('POST', $baseURL.'channels/add', [
                'json' => [
                    'token' => $token,
                    'channelName' => $channel->url,
                ]
            ]);

            Log::info($generalStatistics->error);

            $this->release(60 * 20);

            return;
        }

        $subscribers = json_decode($client->request('GET', $baseURL.'channels/subscribers', ['query' => ['token' => $token, 'channelId' => $channel->url]])->getBody()->getContents());
        $avg_posts_reach = json_decode($client->request('GET', $baseURL.'channels/avg-posts-reach', ['query' => ['token' => $token, 'channelId' => $channel->url]])->getBody()->getContents());
        $er = json_decode($client->request('GET', $baseURL.'channels/er', ['query' => ['token' => $token, 'channelId' => $channel->url]])->getBody()->getContents());

        ChannelStatistic::updateOrCreate(
            ['channel_id' => $channel->id],
            [
                'stats' => json_encode($generalStatistics),
                'subscribers' => json_encode($subscribers),
                'avg_posts_reach' => json_encode($avg_posts_reach),
                'er' => json_encode($er)
            ]
        );

        $channel->status = 'accepted';
        $channel->save();
    }
}
