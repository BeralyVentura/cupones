<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Business;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CuponesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_un_cupon()
    {
        Role::firstOrCreate(['name' => 'Empresa']);

        $empresa = User::factory()->create();
        $empresa->assignRole('Empresa');

        Sanctum::actingAs($empresa, ['*']);

        // Crear un negocio asociado
        $business = Business::factory()->create();

        $data = [
            'title' => 'Cupón 2x1',
            'discount' => 20,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'business_id' => $business->id, 
            'regular_price' => 100,
            'offer_price' => 80
        ];

        $response = $this->postJson('/api/v1/empresa/coupons', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'Cupón 2x1']);

        $this->assertDatabaseHas('coupons', ['title' => 'Cupón 2x1']);
    }
}
