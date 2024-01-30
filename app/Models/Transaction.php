<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = ['id', 'service', 'appointment', 'user_id', 'amount', 'status', 'payment_method', 'details', 'transaction_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
