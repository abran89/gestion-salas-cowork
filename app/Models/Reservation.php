<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['room_id', 'user_id', 'fecha_inicio', 'fecha_fin', 'estado'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Esta función comprueba si hay alguna reserva en la misma sala que se solape
     * con el rango de tiempo proporcionado. Si existe alguna reserva que se solape,
     * la sala no estará disponible. El resultado de la consulta se invierte para que
     * el método devuelva `true` si la sala está disponible y `false` si la sala está
     * ocupada por una reserva en ese rango de tiempo.
     *
     * @param int $room_id El ID de la sala
     * @param \Carbon\Carbon $start_time La hora de inicio de la reserva
     * @param \Carbon\Carbon $end_time La hora de fin de la reserva
     *
     * @return bool Retorna `true` si la sala está disponible, `false` si está ocupada
     */
    public static function isAvailable($room_id, $start_time, $end_time)
    {
        return !self::where('room_id', $room_id)
            ->where(function ($query) use ($start_time, $end_time) {
                $query->whereBetween('fecha_inicio', [$start_time, $end_time])
                      ->orWhereBetween('fecha_fin', [$start_time, $end_time]);
            })
            ->exists();
    }
}
