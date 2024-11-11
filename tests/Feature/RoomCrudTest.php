<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prueba para verificar que solo los administradores pueden crear salas
     *
     * Este método realiza lo siguiente:
     * - Crea un usuario administrador
     * - Crea un usuario normal
     * - Simula que un administrador intenta crear una sala y verifica que la creación sea exitosa
     * - Simula que un usuario normal intenta crear una sala y verifica que se le deniegue el acceso
     * - Verifica que la sala creada por el administrador se haya almacenado en la base de datos
     * - Verifica que el usuario normal reciba un error de acceso denegado
     *
     * @return void
     */
    public function test_only_admins_can_create_rooms()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($admin)->post('/rooms', [
            'nombre' => 'Sala de prueba',
            'descripcion' => 'Descripción de la sala',
        ]);
        $response->assertRedirect('/rooms');
        $this->assertDatabaseHas('rooms', ['nombre' => 'Sala de prueba']);

        $response = $this->actingAs($user)->post('/rooms', [
            'nombre' => 'Sala no autorizada',
            'descripcion' => 'Descripción no permitida',
        ]);
        $response->assertStatus(403);
    }

    /**
     * Prueba para verificar que solo los administradores pueden editar salas
     *
     * Este método realiza lo siguiente:
     * - Crea un usuario administrador
     * - Crea un usuario normal
     * - Crea una sala
     * - Simula que un administrador intenta editar una sala y verifica que la edición sea exitosa
     * - Verifica que la sala se haya actualizado en la base de datos
     * - Simula que un usuario normal intenta editar una sala y verifica que se le deniegue el acceso
     * - Verifica que el usuario normal reciba un error de acceso denegado
     *
     * @return void
     */
    public function test_only_admins_can_edit_rooms()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $user = User::factory()->create();

        $room = Room::factory()->create();

        $response = $this->actingAs($admin)->put('/rooms/update/' . $room->id, [
            'nombre' => 'Sala actualizada',
            'descripcion' => 'Descripción actualizada',
        ]);
        $response->assertRedirect('/rooms');
        $this->assertDatabaseHas('rooms', ['nombre' => 'Sala actualizada']);

        $response = $this->actingAs($user)->put('/rooms/update/' . $room->id, [
            'nombre' => 'Sala no autorizada',
            'descripcion' => 'Descripción no permitida',
        ]);
        $response->assertStatus(403);
    }

    /**
     * Prueba para verificar que solo los administradores pueden eliminar salas
     *
     * Este método realiza lo siguiente:
     * - Crea un usuario administrador
     * - Crea una sala
     * - Simula que un administrador intenta eliminar una sala y verifica que la eliminación sea exitosa
     * - Verifica que la sala haya sido eliminada de la base de datos
     * - Crea un usuario normal
     * - Simula que un usuario normal intenta eliminar una sala y verifica que se le deniegue el acceso
     * - Verifica que el usuario normal reciba un error de acceso denegado
     *
     * @return void
     */
    public function test_only_admins_can_delete_rooms()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);
        $room = Room::factory()->create();

        $response = $this->actingAs($admin)->delete('/rooms/delete/' . $room->id);
        $response->assertRedirect('/rooms');
        $this->assertDatabaseMissing('rooms', ['id' => $room->id]);

        $user = User::factory()->create();

        $room = Room::factory()->create();

        $response = $this->actingAs($user)->delete('/rooms/delete/' . $room->id);

        $response->assertStatus(403);
    }
}
