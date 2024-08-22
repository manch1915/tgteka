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

class FetchChannelStatisticsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Channel $channel;

    protected Client $client;
    public int $tries = 5;
    private string $token;

    private string $baseURL;

    public function __construct(Channel $channel)
    {
        $this->channel = $channel;
        $this->token = config('app.api_token');
        $this->baseURL = config('app.api_base_url');
    }

    public function handle(): void
    {
        $this->client = new Client();

        try {
            $generalStatistics = $this->fetchGeneralStatistics();

            if (!$generalStatistics) return;

            $this->updateChannelStatistics($generalStatistics);
            $this->updateChannelDetails($generalStatistics);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            logger('Fetching general statistics failed', [
                'exception' => $e->getMessage(),
                'channel' => $this->channel->id,
            ]);
            $this->channel->status = 'loading';
            $this->channel->save();

            $this->release();

            return;
        }
    }

    /**
     * @throws GuzzleException
     */
    private function fetchGeneralStatistics()
    {
        $generalStatisticsResponse = $this->client->request('GET', $this->baseURL.'channels/stat', [
            'query' => [
                'token' => $this->token,
                'channelId' => $this->channel->url
            ]
        ]);

        $generalStatistics = json_decode($generalStatisticsResponse->getBody()->getContents());

        if ($generalStatistics->status === "error") {
            switch ($generalStatistics->error) {
                case 'channel_not_found':
                    // Handle channel not found error
                    logger()->info('Ошибка: Канал не найден.');
                    $this->client->request('POST', $this->baseURL . 'channels/add', ['json' => ['token' => $this->token, 'channelName' => $this->channel->url]]);
                    $this->release(60 * 20);
                    return false;

                case 'quota_requests_reached':
                    // Handle quota requests reached error
                    logger()->error('Ошибка: Превышена квота на общее кол-во запросов в месяц.');
                    // You can handle it as needed here
                    return false;

                case 'quota_channel_reached':
                    // Handle quota channel reached error
                    logger()->error('Ошибка: Превышена квота на кол-во уникальных каналов в месяц.');
                    // You can handle it as needed here
                    return false;

                case 'quota_keywords_reached':
                    // Handle quota keywords reached error
                    logger()->error('Ошибка: Превышена квота на кол-во уникальных ключевых слов в месяц.');
                    // You can handle it as needed here
                    return false;

                default:
                    logger()->error('Неизвестная ошибка: ' . $generalStatistics->error);
                    return false;
            }
        }

        return $generalStatistics;
    }


    /**
     * @throws GuzzleException
     */
    private function updateChannelStatistics($generalStatistics): void
    {
        // request subscriber, avg_posts_reach and engagementRate
        $subscribers = $this->fetchData('channels/subscribers');
        $avg_posts_reach = $this->fetchData('channels/avg-posts-reach');
        $engagementRate = $this->fetchData('channels/er'); // renamed from 'er'

        ChannelStatistic::updateOrCreate(
            ['channel_id' => $this->channel->id],
            [
                'stats' => json_encode($generalStatistics->response),
                'subscribers' => json_encode($subscribers->response ?? []),
                'avg_posts_reach' => json_encode($avg_posts_reach->response ?? []),
                'er' => json_encode($engagementRate->response ?? []) // renamed from 'er'
            ]
        );
    }

    /**
     * @throws GuzzleException
     */
    private function fetchData($endpoint)
    {
        $response = $this->client->request('GET', $this->baseURL.$endpoint, ['query' => ['token' => $this->token, 'channelId' => $this->channel->url]]);
        return json_decode($response->getBody()->getContents());
    }

    /**
     * @throws GuzzleException
     */
    private function updateChannelDetails($generalStatistics): void
    {
        $channelDetailsResponse = $this->client->request('GET', $this->baseURL.'channels/get', ['query' => ['token' => $this->token, 'channelId' => $this->channel->url]]);
        $channelDetails = json_decode($channelDetailsResponse->getBody()->getContents());

        if(property_exists($channelDetails->response, 'image640')) {
            $this->channel->avatar = $channelDetails->response->image640;
        }

        if(property_exists($channelDetails->response, 'language')) {
            $this->channel->language = $channelDetails->response->language;
        }

        if (property_exists($generalStatistics->response, 'peer_type')) {
            // Access the 'peer_type' property
            if ($generalStatistics->response->peer_type === 'channel'
                && property_exists($generalStatistics->response, 'adv_post_reach_12h')
                && $generalStatistics->response->adv_post_reach_12h != 0)
            {
                $this->channel->cpm = ($this->channel->format_one_price * 1000)
                    / $generalStatistics->response->adv_post_reach_12h;
            }
        } else {
            logger('Property "peer_type" does not exist in the response.');
        }

        $this->channel->status = 'accepted';
        $this->channel->save();
    }
}
