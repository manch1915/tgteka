<?php

namespace App\Services;

use App\Models\Channel;
use App\Models\Pattern;

class AvatarService
{
    public function getAvatarUrlOfChannel(Channel $channel): string
    {
        return $this->getMediaUrl('avatars', $channel) ??
            'https://avatars.dicebear.com/api/bottts/' . $channel->channel_name . '.svg';
    }

    public function getAvatarUrlOfPattern(Pattern $pattern): ?string
    {
        return $this->getMediaUrl('images', $pattern);
    }

    private function getMediaUrl(string $collection, $model): ?string
    {
        $media = $model->getMedia($collection)->last();
        return $media ? $media->getUrl() : null;
    }
}
