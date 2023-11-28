<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'channel_id', 'format_id', 'count', 'price'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function format()
    {
        return $this->belongsTo(Format::class);
    }
}
