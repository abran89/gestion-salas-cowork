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
     * Prueba para verificar que solo los administradores puedan crear salas
     * Se intenta crear una sala como administrador y como usuario regular,
     * verificando que solo el administrador pueda crearla
     *
     * @return void
     */
    public function test_only_admins_can_create_rooms()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $user = User::factory()->create([
            'is_admin' => false,
        ]);

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
     * Prueba para verificar que solo los administradores puedan editar salas
     * Se intenta actualizar una sala como administrador y como usuario regular,
     * verificando que solo el administrador pueda realizar el cambio
     *
     * @return void
     */
    public function test_only_admins_can_edit_rooms()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);
        $room = Room::factory()->create();

        $response = $this->actingAs($admin)->put('/rooms/update/' . $room->id, [
            'nombre' => 'Sala actualizada',
            'descripcion' => 'Descripción actualizada',
        ]);
        $response->assertRedirect('/rooms');
        $this->assertDatabaseHas('rooms', ['nombre' => 'Sala actualizada']);

        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $response = $this->actingAs($user)->put('/rooms/update/' . $room->id, [
            'nombre' => 'Sala no autorizada',
            'descripcion' => 'Descripción no permitida',
        ]);
        $response->assertStatus(403);
    }

    /**
     * Prueba para verificar que solo los administradores puedan eliminar salas
     * Se intenta eliminar una sala como administrador y como usuario regular,
     * verificando que solo el administrador pueda eliminarla
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

        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $room = Room::factory()->create();

        $response = $this->actingAs($user)->delete('/rooms/delete/' . $room->id);

        $response->assertStatus(403);
    }
}
