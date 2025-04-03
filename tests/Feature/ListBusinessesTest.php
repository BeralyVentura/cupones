<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ListBusinessesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usuario_autenticado_puede_listar_todos_los_negocios()
    {
        // Crear usuario autenticado (sin importar el rol)
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        // Crear algunos negocios en la BD
        Business::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/businesses');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function usuario_no_autenticado_no_puede_listar_negocios()
    {
        $response = $this->getJson('/api/v1/businesses');

        $response->assertStatus(401); // No autorizado
    }
}
