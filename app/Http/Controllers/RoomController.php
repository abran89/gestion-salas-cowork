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
     * Muestra la lista de todas las salas
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $rooms = Room::all();
        return view('rooms.index', compact('rooms'));
    }

    /**
     * Muestra el formulario para crear una nueva sala
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('rooms.create');
    }

    /**
     * Guarda una nueva sala en la base de datos, posterior a la validacion del formulario
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
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
     * Muestra el formulario para editar una sala
     *
     * @param \App\Models\Room $room
     * @return \Illuminate\View\View
     */
    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    /**
     * Actualiza los datos de una sala, posterior a la validacion del formulario
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Room $room
     * @return \Illuminate\Http\RedirectResponse
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
     * Elimina una sala
     *
     * @param \App\Models\Room $room
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('rooms.index');
    }
}

