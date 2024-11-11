<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Muestra una lista de todas las salas disponibles
     *
     * Este método realiza lo siguiente:
     * - Obtiene todas las salas desde la base de datos utilizando el modelo `Room`
     * - Redirige a la vista `rooms.index` con los datos de las salas
     *
     * @return \Illuminate\View\View Devuelve la vista con la lista de salas disponibles
     */
    public function index()
    {
        $rooms = Room::all();
        return view('rooms.index', compact('rooms'));
    }

    /**
     * Muestra el formulario para crear una nueva sala
     *
     * Este método realiza lo siguiente:
     * - Retorna la vista `rooms.create`, que contiene el formulario para crear una nueva sala
     *
     * @return \Illuminate\View\View Devuelve la vista del formulario para crear una nueva sala
     */
    public function create()
    {
        return view('rooms.create');
    }

    /**
     * Almacena una nueva sala en la base de datos
     *
     * Este método realiza lo siguiente:
     * - Valida los datos recibidos desde el formulario de creación de sala
     * - Si los datos son válidos, crea una nueva sala con la información proporcionada
     * - Asocia la sala al usuario autenticado mediante `user_id`
     * - Redirige al usuario a la lista de salas
     *
     * @param \Illuminate\Http\Request $request Los datos del formulario de creación de sala
     * @return \Illuminate\Http\RedirectResponse Redirige a la vista de listado de salas
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        Room::create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('rooms.index');
    }


    /**
     * Muestra el formulario de edición para una sala específica
     *
     * Este método realiza lo siguiente:
     * - Recupera la sala con el ID proporcionado a través de la ruta
     * - Pasa la información de la sala a la vista para que pueda ser editada
     * - Muestra el formulario de edición de la sala, permitiendo al usuario modificar los datos
     *
     * @param \App\Models\Room $room La sala a editar
     * @return \Illuminate\View\View La vista del formulario de edición con los datos de la sala
     */
    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    /**
     * Actualiza los datos de una sala existente
     *
     * Este método realiza lo siguiente:
     * - Valida los datos del formulario de edición, asegurándose de que el nombre de la sala sea obligatorio y que no exceda los 255 caracteres
     * - Si los datos son válidos, actualiza la sala en la base de datos
     * - Redirige al usuario de vuelta al listado de salas después de la actualización
     *
     * @param \Illuminate\Http\Request $request Los datos del formulario de edición
     * @param \App\Models\Room $room La sala a actualizar
     * @return \Illuminate\Http\RedirectResponse Redirige al listado de salas después de la actualización
     */
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string'
        ]);

        $room->update($validated);

        return redirect()->route('rooms.index');
    }

    /**
     * Elimina una sala de la base de datos
     *
     * Este método realiza lo siguiente:
     * - Elimina la sala especificada de la base de datos
     * - Redirige al usuario de vuelta al listado de salas después de la eliminación
     *
     * @param \App\Models\Room $room La sala que se desea eliminar
     * @return \Illuminate\Http\RedirectResponse Redirige al listado de salas después de la eliminación
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('rooms.index');
    }
}

