<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Business;

class BusinessPolicy
{
    public function create(User $user)
    {
        return $user->hasRole('Comerciante'); // Solo comerciantes pueden crear negocios
    }

    public function update(User $user, Business $business)
    {
        return $user->hasRole('Administrador') || $user->id === $business->user_id;
    }

    public function delete(User $user, Business $business)
    {
        return $user->hasRole('Administrador') || $user->id === $business->user_id;
    }
}
