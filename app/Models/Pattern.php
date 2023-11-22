<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
/**
 * App\Models\Pattern
 *
 * @property int $id
 * @property int $user_id
 * @property string $media
 * @property string $body
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\PatternFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Pattern newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pattern newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pattern query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pattern whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pattern whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pattern whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pattern whereMedia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pattern whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pattern whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pattern whereUserId($value)
 * @mixin \Eloquent
 */
class Pattern extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['user_id','status', 'body', 'media','orders_count'];
    protected $attributes = [
        'title' => 'Название шаблона',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
