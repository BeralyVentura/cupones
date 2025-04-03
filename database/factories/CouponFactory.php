<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Coupon;

class CouponFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->word(),
            'discount' => $this->faker->numberBetween(5, 50),
            'business_id' => 1, // Puedes variar esto si tienes negocios en tu base
            'regular_price' => 130,
            'offer_price' => 100,
            'start_date' => now()->subDays(2),
            'end_date' => now()->addDays(5),
        ];
    }
}                                                              