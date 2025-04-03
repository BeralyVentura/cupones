<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessFactory extends Factory
{
    protected $model = Business::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Crea un usuario relacionado automáticamente
            'name' => $this->faker->company(),
            'category' => $this->faker->word(),
            'contact' => $this->faker->phoneNumber()
        ];
    }
}