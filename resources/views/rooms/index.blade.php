@extends('home')

@section('dashboard-content')
    <!-- Lista de Salas -->
    <div class="table-responsive">
        <table class="table table-bordered table-dark">
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
                            <!-- Botón de modificar (usando ruta de actualización) -->
                            <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-warning btn-sm">Modificar</a>

                            <!-- Formulario de eliminar (con confirmación) -->
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
@endsection
