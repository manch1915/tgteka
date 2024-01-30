<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
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
