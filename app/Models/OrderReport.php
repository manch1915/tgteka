<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderReport extends Model
{
    use SoftDeletes;

    protected $fillable = ['id', 'order_id', 'message', 'status'];

    public function order(): belongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
