<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Pattern extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['user_id','status', 'body', 'media','orders_count'];

    protected $attributes = [
        'title' => 'Название шаблона',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Check if the pattern has any video files associated with it.
     *
     * @return bool
     */
    public function hasVideo(): bool
    {
        // Check if any media items are videos
        return $this->getMedia()
            ->filter(function (Media $media) {
                return $media->mime_type === 'video/mp4' || $media->mime_type === 'video/quicktime';
            })
            ->isNotEmpty();
    }
}
