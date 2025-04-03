<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Coupon;
use App\Models\Business;
use App\Models\User;

uses(RefreshDatabase::class);

// 🟩 Tests para usuarios con rol Empresa
describe('Empresa autenticada', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->user->assignRole('Empresa'); // ← ✅ Asigna el rol Empresa

        $this->business = Business::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->actingAs($this->user, 'sanctum');
    });

    it('puede crear un cupón', function () {
        $data = [
            'title' => 'PestCoupon',
            'discount' => 20,
            'start_date' => '2025-04-03',
            'end_date' => '2025-04-12',
            'business_id' => $this->business->id,
            'regular_price' => 130,
            'offer_price' => 100
        ];

        $response = $this->postJson('/api/v1/empresa/coupons', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'PestCoupon']);

        $this->assertDatabaseHas('coupons', ['title' => 'PestCoupon']);
    });

    it('no permite crear cupón si end_date es antes de start_date', function () {
        $data = [
            'title' => 'FechaError',
            'discount' => 10,
            'start_date' => '2025-04-10',
            'end_date' => '2025-04-01',
            'business_id' => $this->business->id,
            'regular_price' => 130,
            'offer_price' => 100
        ];

        $response = $this->postJson('/api/v1/empresa/coupons', $data);

        $response->assertStatus(422);
    });

    it('puede actualizar un cupón', function () {
        $coupon = Coupon::factory()->create([
            'business_id' => $this->business->id,
        ]);

        $response = $this->putJson("/api/v1/empresa/coupons/{$coupon->id}", [
            'title' => 'Actualizado',
            'discount' => $coupon->discount,
            'start_date' => $coupon->start_date,
            'end_date' => $coupon->end_date,
            'business_id' => $coupon->business_id,
            'regular_price' => $coupon->regular_price,
            'offer_price' => $coupon->offer_price
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Actualizado']);
    });

    it('puede eliminar un cupón', function () {
        $coupon = Coupon::factory()->create([
            'business_id' => $this->business->id,
        ]);

        $response = $this->deleteJson("/api/v1/empresa/coupons/{$coupon->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('coupons', ['id' => $coupon->id]);
    });

    it('puede redimir un cupón válido', function () {
        $coupon = Coupon::factory()->create([
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'business_id' => $this->business->id,
        ]);

        $response = $this->postJson("/api/v1/empresa/coupons/{$coupon->id}/redeem");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Cupón canjeado correctamente']);
    });

    it('rechaza redimir cupón expirado', function () {
        $coupon = Coupon::factory()->create([
            'start_date' => now()->subDays(10),
            'end_date' => now()->subDays(1),
            'business_id' => $this->business->id,
        ]);

        $response = $this->postJson("/api/v1/empresa/coupons/{$coupon->id}/redeem");

        $response->assertStatus(400)
                 ->assertJsonFragment(['message' => 'Este cupón ha expirado y no puede ser canjeado.']);
    });
});

// 🟦 Tests para usuarios con rol Usuario
describe('Usuario autenticado', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->user->assignRole('Usuario'); // ← ✅ Asigna el rol Usuario
        $this->actingAs($this->user, 'sanctum');
    });

    it('puede listar cupones', function () {
        Coupon::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/usuario/coupons');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    });

    it('puede ver un cupón específico', function () {
        $coupon = Coupon::factory()->create();

        $response = $this->getJson("/api/v1/usuario/coupons/{$coupon->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => $coupon->title]);
    });
});