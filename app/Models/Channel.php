<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Overtrue\LaravelFavorite\Traits\Favoriteable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Channel extends Model implements HasMedia
{
    use HasFactory, Favoriteable, InteractsWithMedia;

    protected $fillable = ['id', 'user_id', 'channel_url', 'channel_name', 'telegram_username', 'avatar', 'topic', 'language', 'description', 'format_one', 'format_two', 'format_three', 'no_deletion', 'repost', 'repeat_discount', 'male_percentage','score', 'rating', 'likes_count', 'views_count', 'status', 'url', 'topic_id', 'type', 'subscribers_source', 'format_one_price', 'format_two_price', 'format_three_price', 'no_deletion_price', 'repost_price', 'channel_creation_date' , 'registerMediaConversionsUsingModelInstance'];

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

}
