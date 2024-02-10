<?php

namespace App\Services;

use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelService
{
    protected AvatarService $avatarService;

    public function __construct(AvatarService $avatarService)
    {
        $this->avatarService = $avatarService;
    }

    public function fetchChannels(Request $request)
    {
        $search = $request->input('search');
        $order = $request->input('order');
        $sort = $request->input('sort', 'desc');

        $channelsQuery = Channel::query()
            ->where('status', '=', 'accepted')
            ->leftJoin('channel_statistics', 'channels.id', '=', 'channel_statistics.channel_id')
            ->whereNotNull('channel_statistics.stats');

        if ($order) {

            $channelFields = ['cpm', 'format_one_price', 'rating'];
            if (in_array($order, $channelFields)) {
                $orderDirection = $sort === 'desc' ? 'desc' : 'asc';
                $channelsQuery->whereNotNull($order)->orderBy($order, $orderDirection);
            } else {
                $channelsQuery->whereRaw("JSON_CONTAINS_PATH(channel_statistics.stats, 'one', '$." . $order . "')");
                $orderDirection = $sort === 'desc' ? 'desc' : 'asc';
                $channelsQuery->orderByRaw("CAST(JSON_EXTRACT(channel_statistics.stats, '$." . $order . "') as UNSIGNED) " . $orderDirection);
            }
        }

        if ($search) {
            $channelsQuery->where('channel_name', 'like', '%' . $search . '%');
        }

        $this->applyAdditionalFilters($channelsQuery, $request);
        $this->applyMainFilters($channelsQuery, $request);

        return $channelsQuery->paginate(10)->each(function ($channel) use ($request) {
            $channel->isFav = $request->user()->hasFavorited($channel);
            $channel->avatar = $this->avatarService->getAvatarUrlOfChannel($channel);
            $channel->statistics = $channel->stats ? json_decode($channel->stats, true) : [];
        });
    }

    protected function applyAdditionalFilters($query, Request $request): void
    {
        foreach ($request->input() as $key => $value) {
            if ($key === 'peerType') {
                $query->where('type', '=', $value);
            } elseif (str_contains($key, '_min')) {
                $field = str_replace('_min', '', $key);
                $query->whereRaw("CAST(JSON_EXTRACT(channel_statistics.stats, '$." . $field . "') as UNSIGNED) >= ?", [$value]);
            } elseif (str_contains($key, '_max')) {
                $field = str_replace('_max', '', $key);
                $query->whereRaw("CAST(JSON_EXTRACT(channel_statistics.stats, '$." . $field . "') as UNSIGNED) <= ?", [$value]);
            }
        }
    }

    protected function applyMainFilters($query, Request $request): void
    {
        $channel_creation_date = $request->input('channel_creation_date');
        $male_percentage = $request->input('male_percentage');
        $female_percentage = $request->input('female_percentage');

        if ($channel_creation_date) {
            $query->whereRaw('TIMESTAMPDIFF(MONTH, channel_creation_date, CURRENT_DATE) > ?', [$channel_creation_date]);
        }
        if ($male_percentage) {
            $query->where('male_percentage', '>=', $male_percentage);
        }
        if ($female_percentage) {
            $query->whereRaw('(100 - male_percentage) >= ?', [$female_percentage]);
        }
    }
}
