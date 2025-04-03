<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UpdateBusinessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function empresa_autenticada_puede_actualizar_un_negocio()
    {
        // Crear rol y usuario con rol Empresa
        Role::firstOrCreate(['name' => 'Empresa']);
        $user = User::factory()->create();
        $user->assignRole('Empresa');
        Sanctum::actingAs($user, ['*']);

        // Crear un negocio existente
        $business = Business::factory()->create([
            'name' => 'Nombre original',
            'category' => 'Comida',
            'contact' => 'contacto@original.com',
            'address' => 'Dirección vieja',
            'phone' => '1111-2222',
        ]);

// Elimina 'address' de aquí
$updatedData = [
    'name' => 'Negocio Actualizado',
    'category' => 'Tecnología',
    'contact' => 'nuevo@correo.com',
    'phone' => '7777-8888',
];


        $response = $this->putJson("/api/v1/empresa/businesses/{$business->id}", $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'name' => 'Negocio Actualizado',
                     'category' => 'Tecnología',
                     'contact' => 'nuevo@correo.com',
                 ]);

        $this->assertDatabaseHas('businesses', [
            'id' => $business->id,
            'name' => 'Negocio Actualizado',
            'contact' => 'nuevo@correo.com',
        ]);
    }
}
