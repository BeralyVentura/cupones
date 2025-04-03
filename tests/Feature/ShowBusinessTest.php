<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ShowBusinessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function empresa_autenticada_puede_ver_un_negocio_por_id()
    {
        // Crear el rol y un usuario con ese rol
        Role::firstOrCreate(['name' => 'Empresa']);
        $user = User::factory()->create();
        $user->assignRole('Empresa');
        Sanctum::actingAs($user, ['*']);

        // Crear un negocio
        $business = Business::factory()->create([
            'name' => 'Negocio Visible',
            'category' => 'Tecnología',
            'contact' => 'contacto@negocio.com'
        ]);

        // Hacer GET al endpoint
        $response = $this->getJson("/api/v1/empresa/businesses/{$business->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'name' => 'Negocio Visible',
                     'category' => 'Tecnología',
                     'contact' => 'contacto@negocio.com',
                 ]);
    }

    /** @test */
    public function negocio_inexistente_devuelve_404()
    {
        Role::firstOrCreate(['name' => 'Empresa']);
        $user = User::factory()->create();
        $user->assignRole('Empresa');
        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/v1/empresa/businesses/999');

        $response->assertStatus(404);
    }
}
