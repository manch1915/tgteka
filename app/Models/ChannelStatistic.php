<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChannelStatistic extends Model
{
    use SoftDeletes;

    protected $fillable = ['channel_id', 'stats', 'subscribers', 'avg_posts_reach', 'er'];

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }
}
