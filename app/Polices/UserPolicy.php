<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function create(User $user)
    {
        return $user->hasRole('Administrador');
    }

    public function update(User $user, User $target)
    {
        return $user->hasRole('Administrador');
    }

    public function delete(User $user, User $target)
    {
        return $user->hasRole('Administrador');
    }
}