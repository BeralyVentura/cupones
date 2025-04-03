<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run()
    {
        // Crear roles (sin duplicar)
        Role::firstOrCreate(['name' => 'Administrador']);
        Role::firstOrCreate(['name' => 'Usuario']);
        Role::firstOrCreate(['name' => 'Empresa']);

        // Crear usuario administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@cuponera.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('admin1234'),
            ]
        );

        // Asignar rol administrador si no lo tiene
        if (!$admin->hasRole('Administrador')) {
            $admin->assignRole('Administrador');
        }
    }
}
