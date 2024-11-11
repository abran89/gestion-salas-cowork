<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Room;
use App\Models\User;
use App\Models\Reservation;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

     /**
     * Prueba para verificar que un usuario autenticado pueda crear una reserva para una sala
     * especificando las fechas de inicio y fin, y que la reserva se guarde correctamente en
     * la base de datos
     *
     * @return void
     */
    public function test_user_can_create_a_reservation()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $room = Room::factory()->create();

        $response = $this->post(route('reservations.store'), [
            'room_id' => $room->id,
            'fecha_inicio' => Carbon::now()->addHours(2)->toDateTimeString()
        ]);

        $response->assertRedirect(route('reservations.index'));

        $this->assertDatabaseHas('reservations', [
            'room_id' => $room->id,
            'estado' => 'Pendiente',
            'user_id' => $user->id
        ]);
    }

    /**
     * Prueba para verificar que si un usuario intenta hacer una reserva en un horario que ya
     * está ocupado por otra reserva, se muestre un error en la sesión indicando que la sala
     * ya está reservada para ese horario
     *
     * @return void
     */
    public function test_user_cannot_create_a_reservation_for_an_occupied_room()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $room = Room::factory()->create();

        Reservation::create([
            'room_id' => $room->id,
            'user_id' => $user->id,
            'fecha_inicio' => Carbon::now()->addHours(2),
            'fecha_fin' => Carbon::now()->addHours(3),
            'estado' => 'Pendiente',
        ]);

        $response = $this->post(route('reservations.store'), [
            'room_id' => $room->id,
            'fecha_inicio' => Carbon::now()->addHours(2)->toDateTimeString()
        ]);

        $response->assertSessionHasErrors([
            'fecha_inicio' => 'La sala ya está reservada para ese horario.',
        ]);
    }
}
