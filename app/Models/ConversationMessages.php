<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class ConversationMessages extends Model implements HasMedia
{
    use SoftDeletes, HasFactory, InteractsWithMedia;

    protected $fillable = ['id', 'is_seen', 'user_id', 'conversation_id', 'message'];
    protected $appends = ['created_at_time'];

    public function getCreatedAtTimeAttribute(): string
    {
        return $this->created_at->format('H:i');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
