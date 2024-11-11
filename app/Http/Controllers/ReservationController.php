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
     * Almacena una nueva reserva en la base de datos después de validación y verificación de disponibilidad de la sala
     *
     * Este método realiza lo siguiente:
     * - Valida los datos del formulario, asegurándose de que el ID de la sala exista y que la fecha de inicio sea válida
     * - Obtiene la sala a partir del ID proporcionado
     * - Convierte la fecha de inicio en un objeto `Carbon` y calcula la fecha de fin sumando una hora.
     * - Verifica si la sala está disponible para la fecha y hora especificadas
     * - Si la sala ya está reservada en ese horario, devuelve un error con un mensaje que indica que la sala ya está ocupada
     * - Si la sala está disponible, crea una nueva reserva en la base de datos con los datos proporcionados y la fecha calculada de fin
     * - Redirige al usuario a la página de listado de reservas (`reservations.index`)
     *
     * @param Request $request Datos del formulario de reserva
     * @return \Illuminate\Http\RedirectResponse Redirige a la vista de reservas con la información procesada
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

    /**
     * Muestra el listado de reservas, aplicando filtros según el rol del usuario y los parámetros de búsqueda
     *
     * Este método realiza lo siguiente:
     * - Crea una consulta base para obtener las reservas
     * - Si el usuario autenticado no es administrador, filtra las reservas solo para ese usuario
     * - Si se proporciona un ID de sala en la solicitud, filtra las reservas para esa sala en particular
     * - Obtiene las reservas que cumplen con los filtros, junto con las relaciones necesarias (sala y usuario)
     * - Recupera todas las salas disponibles para mostrarlas en la vista
     * - Devuelve la vista `reservations.index` con las reservas y las salas disponibles
     *
     * @param Request $request Los datos de la solicitud, incluidos los parámetros de búsqueda
     * @return \Illuminate\View\View `reservations.index` con las reservas y las salas disponibles
    */
    public function index(Request $request){

        $query = Reservation::query();

        if (!auth()->user()->is_admin) {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        $reservations = $query->with('room', 'user')->get();

        $rooms = Room::all();

        return view('reservations.index', compact('reservations', 'rooms'));

    }

    /**
     * Muestra el formulario para crear una nueva reserva, con las salas disponibles
     *
     * Este método realiza lo siguiente:
     * - Recupera todas las salas disponibles desde el modelo `Room`
     * - Pasa las salas a la vista `reservations.create` para que el usuario pueda seleccionarlas al crear una reserva
     *
     * @return \Illuminate\View\View `reservations.create` con las salas disponibles para la selección del usuario
    */
    public function create(){

        $rooms = Room::all();
        return view('reservations.create', compact('rooms'));
    }

    /**
     * Actualiza el estado de una reserva específica
     *
     * Este método realiza lo siguiente:
     * - Verifica que el estado de la reserva no haya sido modificado previamente. Si el estado ya no es "Pendiente", se redirige con un mensaje de error
     * - Valida que el estado proporcionado en la solicitud esté dentro de los valores permitidos ("Aceptada" o "Rechazada")
     * - Actualiza el estado de la reserva con el valor proporcionado en la solicitud
     * - Guarda los cambios y redirige al listado de reservas con un mensaje de éxito
     *
     * @param \Illuminate\Http\Request $request Nuevo estado de la sala
     * @param \App\Models\Reservation $reservation La reserva que se desea actualizar
     * @return \Illuminate\Http\RedirectResponse Redirige a la página de reservas con un mensaje de éxito o error
    */
    public function updateStatus(Request $request, Reservation $reservation)
    {
        $reservation = Reservation::findOrFail($reservation->id);

        if ($reservation->estado != 'Pendiente') {
            return redirect()->route('reservations.index')->with('error', 'No se puede cambiar el estado, ya está modificado.');
        }

        $request->validate([
            'estado' => 'required|in:Aceptada,Rechazada'
        ]);

        $reservation->estado = $request->estado;
        $reservation->save();

        return redirect()->route('reservations.index')->with('estado', 'Reserva actualizada correctamente.');
    }
}
