<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Channel;
use App\Models\Pattern;
use App\Policies\ChannelPolicy;
use App\Policies\PatternPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
        Channel::class => ChannelPolicy::class,
        Pattern::class => PatternPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
