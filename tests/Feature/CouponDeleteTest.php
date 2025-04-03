<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CouponDeleteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function empresa_puede_eliminar_un_cupon()
    {
        // Crear rol y usuario
        Role::firstOrCreate(['name' => 'Empresa']);
        $user = User::factory()->create();
        $user->assignRole('Empresa');
        Sanctum::actingAs($user, ['*']);

        // Crear negocio y cupón asociado
        $business = Business::factory()->create();
        $coupon = Coupon::factory()->create([
            'business_id' => $business->id,
            'title' => 'Cupón a eliminar',
            'discount' => 10,
            'regular_price' => 100,
            'offer_price' => 80,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
        ]);

        // Ejecutar la petición DELETE
        $response = $this->deleteJson("/api/v1/empresa/coupons/{$coupon->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Cupón eliminado'
                 ]);

        // Confirmar que fue eliminado de la BD
        $this->assertDatabaseMissing('coupons', [
            'id' => $coupon->id
        ]);
    }
}
