<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Overtrue\LaravelFavorite\Traits\Favoriter;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use HasRoles;
    use Favoriter;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile_number',
        'telegram_username',
        'telegram_user_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
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

    public function channels()
    {
        return $this->hasMany(Channel::class);
    }

    public function channelOrders()
    {
        return $this->hasManyThrough(Order::class, Channel::class);
    }

    public function patterns()
    {
        return $this->hasMany(Pattern::class);
    }

    public function orders()
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

}
