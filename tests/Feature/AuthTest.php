<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_registrar_un_usuario_con_rol_usuario()
    {
        // Asegurar que el rol 'Usuario' exista (Spatie)
        Role::firstOrCreate(['name' => 'Usuario']);

        $data = [
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/v1/auth/register', $data);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'token',
                     'user' => [
                         'id',
                         'name',
                         'email',
                         'created_at',
                         'updated_at'
                     ]
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'juan@example.com',
        ]);
    }
}
