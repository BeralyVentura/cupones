<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DeleteBusinessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function empresa_autenticada_puede_eliminar_un_negocio()
    {
        // Crear rol y usuario
        Role::firstOrCreate(['name' => 'Empresa']);
        $user = User::factory()->create();
        $user->assignRole('Empresa');
        Sanctum::actingAs($user, ['*']);

        // Crear negocio
        $business = Business::factory()->create([
            'user_id' => $user->id,
        ]);

        // Eliminar negocio
        $response = $this->deleteJson("/api/v1/empresa/businesses/{$business->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Negocio eliminado.'
                 ]);

        $this->assertDatabaseMissing('businesses', [
            'id' => $business->id,
        ]);
    }
}
