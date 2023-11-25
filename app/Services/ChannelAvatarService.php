<?php

namespace App\Services;

use App\Models\Channel;

class ChannelAvatarService
{
    public function getAvatarUrl(Channel $channel): string
    {
        $media = $channel->getMedia('avatars')->last();

        if ($media) {
            return $media->getUrl();
        }

        return 'https://avatars.dicebear.com/api/bottts/' . $channel->channel_name . '.svg';
    }
}
