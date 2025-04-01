<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Business;
use App\Policies\UserPolicy;
use App\Policies\CouponPolicy;
use App\Policies\BusinessPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Coupon::class => CouponPolicy::class,
        Business::class => BusinessPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
