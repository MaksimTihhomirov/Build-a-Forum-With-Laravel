<?php

namespace App\Policies;

use App\Models\Like;
use App\Models\User;

class LikePolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function delete(User $user, Like $like): bool
    {
        return $like->user_id === $user->id;
    }
}
