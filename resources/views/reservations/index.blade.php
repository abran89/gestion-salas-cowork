@extends('home')

@section('dashboard-content')
<div class="container mt-4 col-md-12">
    <h1 class="text-white">Listado de Reservas</h1>

    @if(session('estado'))
        <div class="alert alert-success">
            {{ session('estado') }}
        </div>
    @endif

    @if(auth()->user()->is_admin)
        <form method="GET" action="{{ route('reservations.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-6">
                    <label for="room_id" class="form-label text-white">Filtrar por Sala</label>
                    <select name="room_id" id="room_id" class="form-select bg-dark text-white">
                        <option value="">Todas las salas</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>{{ $room->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </div>
        </form>
    @endif

    <!-- Listado de reservas -->
    <div class="card shadow-lg border-0 bg-dark text-white">
        <div class="card-body">
            @if($reservations->isEmpty())
                <p>No hay reservas disponibles.</p>
            @else
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>Sala</th>
                            @if(auth()->user()->is_admin)
                                <th>Usuario</th>
                            @endif
                            <th>Desde</th>
                            <th>Hasta</th>
                            <th>Estado</th>
                            @if(auth()->user()->is_admin)
                                <th>Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $reservation)
                            <tr>
                                <td>{{ $reservation->room->nombre }}</td>
                                @if(auth()->user()->is_admin)
                                <td>{{ $reservation->user->name }}</td>
                                @endif
                                <td>{{ \Carbon\Carbon::parse($reservation->fecha_inicio)->format('d-m-Y H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($reservation->fecha_fin)->format('d-m-Y H:i') }}</td>
                                <td>
                                    @if($reservation->estado == 'Pendiente')
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                    @elseif($reservation->estado == 'Aceptada')
                                        <span class="badge bg-success">Aceptada</span>
                                    @elseif($reservation->estado == 'Rechazada')
                                        <span class="badge bg-danger">Rechazada</span>
                                    @endif
                                </td>

                                @if(auth()->user()->is_admin)
                                    <td>
                                        @error('estado')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror

                                        @if($reservation->estado == 'Pendiente')
                                            <form action="{{ route('reservations.updateStatus', $reservation->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <select name="estado" class="form-select bg-dark text-white d-inline-block w-auto">
                                                    <option value="Aceptada" {{ $reservation->status == 'Aceptada' ? 'selected' : '' }}>Aceptada</option>
                                                    <option value="Rechazada" {{ $reservation->status == 'Rechazada' ? 'selected' : '' }}>Rechazada</option>
                                                </select>
                                                <button type="submit" class="btn btn-primary btn-sm">Cambiar</button>
                                            </form>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
