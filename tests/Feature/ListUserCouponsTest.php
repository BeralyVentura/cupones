<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ListUserCouponsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usuario_autenticado_puede_listar_los_cupones()
    {
        // Crear el rol y el usuario
        Role::firstOrCreate(['name' => 'Usuario']);
        $user = User::factory()->create();
        $user->assignRole('Usuario');
        Sanctum::actingAs($user, ['*']);
    
        // Crear un negocio (Business) válido
        $business = \App\Models\Business::factory()->create();
    
        // Crear cupones asociados a ese negocio
        \App\Models\Coupon::factory()->count(3)->create([
            'business_id' => $business->id,
        ]);
    
        // Hacer GET
        $response = $this->getJson('/api/v1/usuario/coupons');
    
        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }
    

    /** @test */
    public function usuario_no_autenticado_no_puede_listar_cupones()
    {
        $response = $this->getJson('/api/v1/usuario/coupons');

        $response->assertStatus(401); // No autorizado
    }
}

