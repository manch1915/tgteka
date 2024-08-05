<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'description', 'channel_id', 'status', 'pattern_id', 'format_id', 'price', 'id', 'post_date', 'post_date_end', 'near_future', 'refund_issued'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pattern(): BelongsTo
    {
        return $this->belongsTo(Pattern::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function format(): BelongsTo
    {
        return $this->belongsTo(Format::class);
    }

    public function orderReports(): hasMany
    {
        return $this->hasMany(OrderReport::class);
    }

    public function markAsFinished(): void
    {
        $this->update(['status' => 'finished']);
    }

    public function markAsChecked(): void
    {
        $this->update(['status' => 'checked']);
    }

    public function review(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function markAsDeclined(): void
    {
        $this->update(['status' => 'declined']);
    }
}
