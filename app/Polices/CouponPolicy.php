<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Coupon;

class CouponPolicy
{
    public function create(User $user)
    {
        return $user->hasRole('Comerciante');
    }

    public function update(User $user, Coupon $coupon)
    {
        return $user->hasRole('Administrador') || $user->id === $coupon->comercio->user_id;
    }

    public function delete(User $user, Coupon $coupon)
    {
        return $user->hasRole('Administrador') || $user->id === $coupon->comercio->user_id;
    }

    public function redeem(User $user, Coupon $coupon)
    {
        return $user->hasRole(['Empleado', 'Usuario Final']);
    }
}