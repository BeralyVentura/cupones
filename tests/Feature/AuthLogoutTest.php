<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AuthLogoutTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function puede_realizar_logout_correctamente(): void
    {
        // Asegurar que el rol 'Usuario' existe
        Role::firstOrCreate(['name' => 'Usuario']);

        // Crear usuario y asignar rol
        $user = User::factory()->create();
        $user->assignRole('Usuario');

        // Autenticar usuario con Sanctum
        Sanctum::actingAs($user, ['*']);

        // Hacer POST al endpoint de logout
        $response = $this->postJson('/api/v1/auth/logout');

        // Validar respuesta
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Cierre de sesión exitoso'
                 ]);
    }
}