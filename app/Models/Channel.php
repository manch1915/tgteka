<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelFavorite\Traits\Favoriteable;

/**
 * App\Models\Channel
 *
 * @property int $id
 * @property int $user_id
 * @property string $channel_url
 * @property string $channel_name
 * @property string|null $telegram_username
 * @property string|null $avatar
 * @property string $topic
 * @property string $language
 * @property string|null $description
 * @property int $format_one
 * @property int $format_two
 * @property int $format_three
 * @property int $no_deletion
 * @property int $repost
 * @property int $repeat_discount
 * @property float $score
 * @property float $rating
 * @property int $likes_count
 * @property int $views_count
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $favoriters
 * @property-read int|null $favoriters_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Overtrue\LaravelFavorite\Favorite> $favorites
 * @property-read int|null $favorites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\ChannelFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Channel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Channel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Channel query()
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereChannelName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereChannelUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereFormatOne($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereFormatThree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereFormatTwo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereLikesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereNoDeletion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereRepeatDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereRepost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereTelegramUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereTopic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereViewsCount($value)
 * @mixin \Eloquent
 */
class Channel extends Model
{
    use HasFactory, Favoriteable;

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
}
