<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CreateBusinessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function empresa_autenticada_puede_crear_un_negocio()
    {
        // Crear el rol y un usuario con ese rol
        Role::firstOrCreate(['name' => 'Empresa']);
        $user = User::factory()->create();
        $user->assignRole('Empresa');
        Sanctum::actingAs($user, ['*']);
    
        // ✅ Incluimos los campos requeridos: category y contact
        $data = [
            'name' => 'Mi Empresa Test',
            'description' => 'Negocio de prueba',
            'address' => 'San Salvador',
            'phone' => '2222-3333',
            'category' => 'Comida rápida',
            'contact' => '3232323',
        ];
    
        $response = $this->postJson('/api/v1/empresa/businesses', $data);
    
        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'name' => 'Mi Empresa Test'
                 ]);
    
        $this->assertDatabaseHas('businesses', [
            'name' => 'Mi Empresa Test'
        ]);
    }
    
    /** @test */
    public function usuario_sin_autenticacion_no_puede_crear_negocio()
    {
        $response = $this->postJson('/api/v1/empresa/businesses', []);
        $response->assertStatus(401); // No autenticado
    }
}

