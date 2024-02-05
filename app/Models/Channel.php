<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Overtrue\LaravelFavorite\Traits\Favoriteable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Channel extends Model implements HasMedia
{
    use HasFactory, Favoriteable, InteractsWithMedia;

    protected $fillable = ['user_id', 'url', 'channel_name', 'slug', 'topic_id', 'type', 'language', 'description', 'subscribers_source', 'format_one_price', 'format_two_price', 'format_three_price', 'no_deletion_price', 'repost_price', 'repeat_discount', 'cpm', 'male_percentage', 'score', 'rating', 'likes_count', 'views_count', 'status', 'channel_creation_date', 'deleted_at', 'registerMediaConversionsUsingModelInstance'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'channel_id');
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public static function boot(): void
    {
        parent::boot();

        static::saving(function ($channel) {
            $channel->slug = Str::slug($channel->channel_name) . '-' . $channel->id;
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

}
