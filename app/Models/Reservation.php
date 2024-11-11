<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

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
     * Verifica si una sala está disponible para una reserva en el intervalo de tiempo especificado
     *
     * Este método comprueba si hay conflictos de reservas para una sala específica basándose en el
     * intervalo de tiempo proporcionado. Si ya existe una reserva con estado "Pendiente" o "Aceptada"
     * dentro del rango de tiempo indicado, el método retornará `false` (no disponible). De lo contrario,
     * retornará `true` (disponible).
     *
     * @param int $room_id El ID de la sala a verificar.
     * @param \Carbon\Carbon|string $start_time La hora de inicio de la reserva
     * @param \Carbon\Carbon|string $end_time La hora de fin de la reserva
     * @return bool `true` si la sala está disponible, `false` si hay conflicto con una reserva existente
     */
    public static function isAvailable($room_id, $start_time, $end_time)
    {
        return !self::where('room_id', $room_id)
        ->whereIn('estado', ['Pendiente', 'Aceptada'])
        ->where(function ($query) use ($start_time, $end_time) {
            $query->whereBetween('fecha_inicio', [$start_time, $end_time])
                  ->orWhereBetween('fecha_fin', [$start_time, $end_time]);
        })
        ->exists();
    }
}
