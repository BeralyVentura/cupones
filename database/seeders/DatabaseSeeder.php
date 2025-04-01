<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // ← esta es la clave

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Usuario de prueba',
            'email' => 'test@example.com',
            'password' => Hash::make('12345678'),
        ]);
    }
}
