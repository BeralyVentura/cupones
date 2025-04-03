<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ShowUserCouponTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usuario_autenticado_puede_ver_un_cupon_especifico()
    {
        // Crear usuario con rol 'Usuario'
        Role::firstOrCreate(['name' => 'Usuario']);
        $user = User::factory()->create();
        $user->assignRole('Usuario');
        Sanctum::actingAs($user, ['*']);

        // Crear negocio y cupón
        $business = Business::factory()->create();
        $coupon = Coupon::factory()->create([
            'title' => 'Cupón Especial',
            'business_id' => $business->id,
        ]);

        // Consultar cupón por ID
        $response = $this->getJson("/api/v1/usuario/coupons/{$coupon->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $coupon->id,
                     'title' => 'Cupón Especial',
                 ]);
    }

    /** @test */
    public function retorna_404_si_el_cupon_no_existe()
    {
        Role::firstOrCreate(['name' => 'Usuario']);
        $user = User::factory()->create();
        $user->assignRole('Usuario');
        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson("/api/v1/usuario/coupons/9999");

        $response->assertStatus(404);
    }
}
