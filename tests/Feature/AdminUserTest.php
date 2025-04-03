<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function puede_listar_todos_los_usuarios_como_admin(): void
    {
        Role::firstOrCreate(['name' => 'Administrador']);
        $admin = User::factory()->create();
        $admin->assignRole('Administrador');
        Sanctum::actingAs($admin, ['*']);
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/admin/users');
        $response->assertStatus(200)->assertJsonCount(4);
    }

    #[Test]
    public function puede_ver_un_usuario_especifico_como_admin(): void
    {
        Role::firstOrCreate(['name' => 'Administrador']);
        $admin = User::factory()->create();
        $admin->assignRole('Administrador');
        Sanctum::actingAs($admin, ['*']);

        $usuario = User::factory()->create([
            'name' => 'Carlos Tester',
            'email' => 'carlos@test.com',
        ]);

        $response = $this->getJson("/api/v1/admin/users/{$usuario->id}");
        $response->assertStatus(200)->assertJsonFragment([
            'name' => 'Carlos Tester',
            'email' => 'carlos@test.com',
        ]);
    }

    #[Test]
    public function puede_crear_un_usuario_como_admin(): void
    {
        Role::firstOrCreate(['name' => 'Administrador']);
        Role::firstOrCreate(['name' => 'Empresa']);
        $admin = User::factory()->create();
        $admin->assignRole('Administrador');
        Sanctum::actingAs($admin, ['*']);

        $nuevoUsuario = [
            'name' => 'Lucía Rodríguez',
            'email' => 'lucia@example.com',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/v1/admin/users', $nuevoUsuario);

        $response->assertStatus(201)->assertJson([
            'message' => 'Usuario creado y rol asignado',
            'user' => [
                'name' => 'Lucía Rodríguez',
                'email' => 'lucia@example.com',
            ]
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'lucia@example.com',
            'name' => 'Lucía Rodríguez',
        ]);
    }
    #[Test]
    public function puede_actualizar_un_usuario_como_admin(): void
    {
        // Asegurar que el rol 'Administrador' exista
        Role::firstOrCreate(['name' => 'Administrador']);

        // Crear y autenticar admin
        $admin = User::factory()->create();
        $admin->assignRole('Administrador');
        Sanctum::actingAs($admin, ['*']);

        // Crear un usuario a actualizar
        $usuario = User::factory()->create([
            'name' => 'Usuario Original',
            'email' => 'original@example.com'
        ]);

        // Datos para actualizar
        $datosActualizados = [
            'name' => 'Usuario Actualizado',
            'email' => 'actualizado@example.com',
            'password' => 'nuevopass123'
        ];

        // Enviar solicitud PUT
        $response = $this->putJson("/api/v1/admin/users/{$usuario->id}", $datosActualizados);

        // Verificar respuesta
        $response->assertStatus(200)
                ->assertJsonFragment([
                    'name' => 'Usuario Actualizado',
                    'email' => 'actualizado@example.com',
                ]);

        // Verificar en base de datos
        $this->assertDatabaseHas('users', [
            'id' => $usuario->id,
            'name' => 'Usuario Actualizado',
            'email' => 'actualizado@example.com',
        ]);
    }
    #[Test]
    public function puede_eliminar_un_usuario_como_admin(): void
    {
        // Asegurar que el rol 'Administrador' exista
        Role::firstOrCreate(['name' => 'Administrador']);

        // Crear y autenticar admin
        $admin = User::factory()->create();
        $admin->assignRole('Administrador');
        Sanctum::actingAs($admin, ['*']);

        // Crear un usuario a eliminar
        $usuario = User::factory()->create([
            'name' => 'Usuario A Eliminar',
            'email' => 'eliminar@example.com',
        ]);

        // Enviar DELETE
        $response = $this->deleteJson("/api/v1/admin/users/{$usuario->id}");

        // Verificar respuesta y mensaje
        $response->assertStatus(200)
                ->assertJson(['message' => 'Usuario eliminado']);

        // Verificar que el usuario ya no existe
        $this->assertDatabaseMissing('users', [
            'id' => $usuario->id
        ]);
    }
}