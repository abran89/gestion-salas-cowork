<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class AdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prueba para verificar que solo los administradores pueden acceder a las rutas de administración
     *
     * Este método realiza lo siguiente:
     * - Crea un usuario administrador
     * - Crea un usuario normal
     * - Simula el acceso a una ruta protegida como administrador y se verifica que el acceso sea exitoso
     * - Simula el acceso a la misma ruta como un usuario normal y se verifica el acceso denegado
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
