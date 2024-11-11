@extends('home')

@section('dashboard-content')

<div class="container mt-4 col-md-12">
    <h1 class="text-white">Listado de salas</h1>
    <div class="card shadow-lg border-0 bg-dark text-white col-md-12">
        <div class="card-body">
            <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rooms as $room)
                            <tr>
                                <td>{{ $room->nombre }}</td>
                                <td>{{ $room->descripcion }}</td>
                                <td>
                                    <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-warning btn-sm">Modificar</a>

                                    <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta sala?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
    </div>
</div>
@endsection
