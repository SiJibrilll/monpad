<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Group;

class GroupPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasAnyRole(['dosen', 'asisten']);
    }

    public function view(User $user, Group $group)
    {
        return $user->hasAnyRole(['dosen', 'asisten']);
    }

    public function create(User $user)
    {
        return $user->hasRole('dosen');
    }

    public function update(User $user, Group $group)
    {
        return $user->hasRole('dosen');
    }

    public function delete(User $user, Group $group)
    {
        return $user->hasRole('dosen');
    }
}
