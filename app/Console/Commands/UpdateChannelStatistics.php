<?php

namespace App\Console\Commands;

use App\Models\Channel;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Models\ChannelStatistic;

class UpdateChannelStatistics extends Command
{
    protected $signature = 'channel:statistics:update';
    protected $description = 'Update the statistics for each channel';
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws GuzzleException
     */
    public function handle(): void
    {
        $client = new Client();

        $channels = Channel::where('status', 'accepted')->get();

        foreach($channels as $channel) {

            $baseURL = 'https://api.tgstat.ru/';

            $token = 'e3b38be1f953b4118758d333de716a20';

            $generalStatistics = json_decode($client->request('GET', $baseURL.'channels/stat', ['query' => ['token' => $token, 'channelId' => $channel->url]])->getBody()->getContents());
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

            if ($generalStatistics->response->peer_type === 'channel'
                && property_exists($generalStatistics->response, 'adv_post_reach_12h')
                && $generalStatistics->response->adv_post_reach_12h != 0)
            {
                $channel->cpm = ($channel->format_one_price * 1000)
                    / $generalStatistics->response->adv_post_reach_12h;
            }

            $channel->status = 'accepted';
            $channel->save();
        }
    }
}
