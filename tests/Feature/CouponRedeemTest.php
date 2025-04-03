<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CouponRedeemTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function empresa_puede_canjear_un_cupon_valido()
    {
        // Crear rol y usuario con rol Empresa
        Role::firstOrCreate(['name' => 'Empresa']);
        $user = User::factory()->create();
        $user->assignRole('Empresa');
        Sanctum::actingAs($user, ['*']);

        // Crear negocio y cupón canjeable
        $business = Business::factory()->create();
        $coupon = Coupon::factory()->create([
            'title' => 'Cupón válido',
            'discount' => 20,
            'business_id' => $business->id,
            'regular_price' => 100,
            'offer_price' => 80,
            'start_date' => now()->subDay()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
        ]);

        $response = $this->postJson("/api/v1/empresa/coupons/{$coupon->id}/redeem");

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Cupón canjeado correctamente',
                 ]);
    }
}

