<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    /**
     * Se Define el modelo de reserva
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'room_id' => Room::factory(),
            'user_id' => User::factory(),
            'fecha_inicio' => $this->faker->dateTimeBetween('now', '+1 week'),
            'fecha_fin' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
            'estado' => 'Pendiente',
        ];
    }
}
