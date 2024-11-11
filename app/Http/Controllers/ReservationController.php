<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Room;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Almacena una nueva reserva en la base de datos
     *
     * Este método valida los datos de la solicitud para una nueva reserva, verifica si la sala está disponible en el rango de fechas especificado,
     * y luego crea la reserva si la sala está disponible. Si la sala ya está reservada para esas fechas, se devuelve un mensaje de error
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP entrante que contiene los datos de la reserva
     *
     * @return \Illuminate\Http\RedirectResponse Respuesta de redirección a la página de reservas
     */
    public function store(Request $request){

        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'fecha_inicio' => 'required|date'
        ]);

        $room = Room::find($validated['room_id']);

        $start_time = Carbon::parse($request->fecha_inicio);
        $end_time = $start_time->copy()->addHour();

        if (!Reservation::isAvailable($room->id, $start_time, $end_time)) {
            return back()->withErrors(['fecha_inicio' => 'La sala ya está reservada para ese horario.']);
        }

        Reservation::create([
            'room_id' => $room->id,
            'user_id' => auth()->id(),
            'fecha_inicio' => $start_time,
            'fecha_fin' => $end_time
        ]);

        return redirect()->route('reservations.index');
    }

    public function index(){

    }
}
