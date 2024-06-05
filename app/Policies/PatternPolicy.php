<?php

namespace App\Policies;

use App\Models\Pattern;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PatternPolicy
{
    use HandlesAuthorization;

    public function edit(User $user, Pattern $pattern): Response
    {
        return $user->id === $pattern->user_id ? Response::allow()
            : Response::denyWithStatus(404);
    }
}
