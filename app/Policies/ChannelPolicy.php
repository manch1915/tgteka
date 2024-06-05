<?php

namespace App\Policies;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ChannelPolicy
{
    use HandlesAuthorization;

    public function edit(User $user, Channel $channel): Response
    {
        return $user->id === $channel->user_id ? Response::allow()
            : Response::denyWithStatus(404);
    }

    public function update(User $user, Channel $channel): bool
    {
        return $user->id === $channel->user_id;
    }

}
