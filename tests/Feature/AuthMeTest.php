<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AuthMeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function puede_obtener_usuario_autenticado(): void
    {
        // Asegurar que el rol 'Usuario' exista
        Role::firstOrCreate(['name' => 'Usuario']);

        // Crear usuario y asignar rol
        $user = User::factory()->create([
            'name' => 'Usuario Prueba',
            'email' => 'prueba@example.com',
        ]);
        $user->assignRole('Usuario');

        // Autenticar usuario
        Sanctum::actingAs($user, ['*']);

        // Llamar al endpoint
        $response = $this->getJson('/api/v1/auth/me');

        // Verificar respuesta
        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'name' => 'Usuario Prueba',
                     'email' => 'prueba@example.com',
                 ]);
    }
}