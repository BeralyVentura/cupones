<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CouponUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function empresa_puede_actualizar_un_cupon()
    {
        // Crear rol y usuario con rol Empresa
        Role::firstOrCreate(['name' => 'Empresa']);
        $user = User::factory()->create();
        $user->assignRole('Empresa');
        Sanctum::actingAs($user, ['*']);

        // Crear negocio y cupón
        $business = Business::factory()->create();
        $coupon = Coupon::factory()->create([
            'title' => 'Cupón original',
            'discount' => 10,
            'business_id' => $business->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'regular_price' => 100,
            'offer_price' => 80
        ]);

        // Datos actualizados
        $updatedData = [
            'title' => 'Cupón actualizado',
            'discount' => 30, // ✅ Incluido aquí
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(7)->toDateString(),
            'business_id' => $business->id,
            'regular_price' => 150,
            'offer_price' => 120
        ];

        $response = $this->putJson("/api/v1/empresa/coupons/{$coupon->id}", $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'title' => 'Cupón actualizado',
                     'discount' => 30
                 ]);

        $this->assertDatabaseHas('coupons', [
            'id' => $coupon->id,
            'title' => 'Cupón actualizado',
            'discount' => 30
        ]);
    }
}
