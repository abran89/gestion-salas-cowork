<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class AdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;
     /**
     * Prueba para verificar que solo los administradores pueden acceder a rutas protegidas por el middleware admin
     *
     * @return void
     */
    public function test_only_admins_can_access_admin_routes()
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);

        $user = User::factory()->create([
            'email' => 'user@example.com',
            'is_admin' => false,
        ]);

        $response = $this->actingAs($admin)->get('/rooms');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/rooms');
        $response->assertStatus(403);
    }
}
