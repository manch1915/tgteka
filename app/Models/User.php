<?php

namespace App\Models;

use App\Notifications\SendTwoFactorCodeNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Overtrue\LaravelFavorite\Traits\Favoriter;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use Favoriter;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'mobile_number',
        'telegram_username',
        'telegram_user_id',
        'balance',
        'email_verified_at',
        'referral_id',
        'two_factor_expires_at',
        'two_factor_enabled',
        'two_factor_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function decrementBalance($amount): void
    {
        $this->decrement('balance', $amount);
    }

    public function channels(): HasMany
    {
        return $this->hasMany(Channel::class);
    }

    public function channelOrders(): HasManyThrough
    {
        return $this->hasManyThrough(Order::class, Channel::class);
    }

    public function patterns(): HasMany
    {
        return $this->hasMany(Pattern::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function entityInfo(): HasOne
    {
        return $this->hasOne(EntityInfo::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class, 'sender_id');
    }

    public function conversations(): HasMany
    {
        return $this
            ->hasMany(Conversation::class, 'user_one')->orWhere('user_two', '=', $this->id)->with('userOne', 'userTwo');
    }
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function generateTwoFactorCode(): void
    {
        if ($this->two_factor_enabled) {
            $this->timestamps = false;
            $this->two_factor_code = rand(100000, 999999);
            $this->two_factor_expires_at = now()->addMinutes(10);
            $this->save();

            // Send the two-factor code via a notification
            $this->notify(new SendTwoFactorCodeNotification());
        }
    }

    public function resetTwoFactorCode(): void
    {
        $this->update([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ]);
    }

}
