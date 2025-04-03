<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_iniciar_sesion_con_credenciales_correctas()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $data = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/v1/auth/login', $data);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'access_token',
                     'token_type',
                 ]);
    }

    /** @test */
    public function no_puede_iniciar_sesion_con_credenciales_invalidas()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $data = [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ];

        $response = $this->postJson('/api/v1/auth/login', $data);

        $response->assertStatus(401)
                 ->assertJson([
                     'message' => 'Credenciales incorrectas',
                 ]);
    }
}
