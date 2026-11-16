<?php

namespace App\Policies;

use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class LikePolicy
{
    public function create(User $user, Model $likeable): bool
    {
        return ! $likeable->likes()
            ->where('user_id', $user->id)
            ->exists();
    }
}
