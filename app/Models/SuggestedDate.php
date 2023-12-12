<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SuggestedDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'suggested_post_date',
        'order_id'
    ];

    protected $casts = [
        'suggested_post_date' => 'datetime',
    ];

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }
}
