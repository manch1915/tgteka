<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'user_one', 'user_two'];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_one')->orWhere('user_two', $this->id)->with('userOne');
    }
    public function userOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_one');
    }

    public function userTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_two');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ConversationMessages::class);
    }
}
