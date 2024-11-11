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
     * Prueba para verificar que un usuario puede crear una reserva
     *
     * Este método realiza lo siguiente:
     * - Crea un usuario
     * - Inicia sesión como el usuario creado
     * - Crea una sala
     * - Envía una solicitud para crear una reserva para la sala con una fecha de inicio en el futuro
     * - Verifica que la respuesta redirija correctamente al índice de reservas
     * - Verifica que la reserva se haya guardado en la base de datos con el estado "Pendiente" y el usuario correcto
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
     * Prueba para verificar que un usuario no pueda crear una reserva para una sala ocupada
     *
     * Este método realiza lo siguiente:
     * - Crea un usuario
     * - Inicia sesión como el usuario creado
     * - Crea una sala
     * - Crea una reserva en esa sala para el mismo usuario con un horario específico
     * - Intenta crear una segunda reserva para la misma sala y el mismo horario
     * - Verifica que se muestre un error de validación indicando que la sala ya está reservada
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

    /**
     * Prueba para verificar que un usuario administrador pueda ver todas las reservas
     *
     * Este método realiza lo siguiente:
     * - Crea un usuario administrador
     * - Crea un usuario normal
     * - Crea una sala
     * - Crea dos reservas, una para el usuario normal y otra para el administrador
     * - Verifica que el usuario administrador puede acceder a la página de reservas
     * - Verifica que la vista contiene todas las reservas creadas
     *
     * @return void
     */
    public function test_admin_user_sees_all_reservations()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $room = Room::factory()->create();

        Reservation::factory()->create(['user_id' => $user->id, 'room_id' => $room->id]);
        Reservation::factory()->create(['user_id' => $admin->id, 'room_id' => $room->id]);

        $response = $this->actingAs($admin)->get(route('reservations.index'));

        $response->assertStatus(200);
        $response->assertViewHas('reservations', function ($reservations) {
            return $reservations->count() == Reservation::count();
        });
    }

    /**
     * Prueba para verificar que un usuario normal solo puede ver sus propias reservas
     *
     * Este método realiza lo siguiente:
     * - Crea dos usuarios normales
     * - Crea una sala
     * - Crea dos reservas, una para cada usuario
     * - Verifica que el usuario solo pueda ver su propia reserva
     * - Verifica que el usuario no pueda ver la reserva del otro usuario
     *
     * @return void
     */
    public function test_normal_user_sees_only_own_reservations()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $room = Room::factory()->create();

        Reservation::factory()->create(['user_id' => $user->id, 'room_id' => $room->id]);
        Reservation::factory()->create(['user_id' => $user2->id, 'room_id' => $room->id]);

        $response = $this->actingAs($user)->get(route('reservations.index'));

        $response->assertStatus(200);

        $response->assertViewHas('reservations', function ($reservations) use ($user) {
            return $reservations->count() == Reservation::where('user_id', $user->id)->count();
        });

        $response->assertDontSee($user2->name);
    }

    /**
     * Prueba para verificar que un administrador pueda cambiar el estado de una reserva
     * y que no pueda cambiar el estado si ya ha sido modificado
     *
     * Este método realiza lo siguiente:
     * - Crea un usuario administrador
     * - Crea un usuario normal
     * - Crea una sala
     * - Crea una reserva con estado "Pendiente" por defecto
     * - Verifica que el administrador pueda cambiar el estado de la reserva a "Aceptada"
     * - Verifica que no se puede cambiar el estado si la reserva ya tiene un estado distinto a "Pendiente"
     * - Verifica que el mensaje de error se muestra cuando se intenta cambiar el estado después de ser modificado
     *
     * @return void
     */
    public function test_admin_can_change_reservation_status()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $room = Room::factory()->create();

        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'room_id' => $room->id
        ]);

        $response = $this->actingAs($admin)->patch(route('reservations.updateStatus', $reservation->id), [
            'estado' => 'Aceptada',
        ]);

        $reservation->refresh();
        $this->assertEquals('Aceptada', $reservation->estado);

        $response->assertRedirect(route('reservations.index'));
        $response->assertSessionHas('estado', 'Reserva actualizada correctamente.');

        $response = $this->actingAs($admin)->patch(route('reservations.updateStatus', $reservation->id), [
            'estado' => 'Rechazada',
        ]);

        $reservation->refresh();
        $this->assertEquals('Aceptada', $reservation->estado);

        $response->assertRedirect(route('reservations.index'));
        $response->assertSessionHas('error', 'No se puede cambiar el estado, ya está modificado.');
    }

    /**
     * Prueba para verificar que un usuario no puede modificar el estado de una reserva
     *
     * Este método realiza lo siguiente:
     * - Crea un usuario normal
     * - Crea una sala
     * - Crea una reserva con estado "Pendiente" por defecto
     * - Verifica que el usuario normal no pueda cambiar el estado de la reserva
     * - Verifica que el usuario normal reciba un mensaje de error
     *
     * @return void
     */
    public function test_regular_user_cannot_change_reservation_status()
    {
        $user = User::factory()->create();
        $room = Room::factory()->create();

        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'room_id' => $room->id
        ]);

        $response = $this->actingAs($user)->patch(route('reservations.updateStatus', $reservation->id), [
            'estado' => 'Aceptada',
        ]);

        $reservation->refresh();
        $this->assertEquals('Pendiente', $reservation->estado);

        $response->assertStatus(403);
    }
}
