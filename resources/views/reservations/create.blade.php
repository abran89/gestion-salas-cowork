@extends('home')

@section('dashboard-content')
    <div class="card shadow-lg border-0 col-md-6" style="border-radius: 15px; background-color: #1e1e1e;">
        <div class="card-header text-center bg-dark text-white" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
            <h4 class="mb-0">Crear Reserva</h4>
        </div>
        <div class="card-body text-white">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('reservations.store') }}" method="POST" onsubmit="return combinarFechaHora(event)">
                @csrf
                <div class="mb-3">
                    <label for="room_id" class="form-label">Sala</label>
                    <select class="form-control bg-dark text-white border-0" id="room_id" name="room_id" required>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                    <input type="date" class="form-control bg-dark text-white border-0" id="fecha_inicio" required>
                </div>

                <div class="mb-3">
                    <label for="hora_inicio" class="form-label">Hora de Inicio</label>
                    <input type="time" class="form-control bg-dark text-white border-0" id="hora_inicio" required>
                </div>

                <input type="hidden" id="combined_fecha_inicio" name="fecha_inicio">

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary w-100" style="background-color: #007bff;">Crear Reserva</button>
                </div>
            </form>
        </div>
    </div>

    <script>
       function combinarFechaHora(event) {

        event.preventDefault();

        const fechaInput = document.getElementById('fecha_inicio');
        const horaInput = document.getElementById('hora_inicio');
        const combinedFechaInput = document.getElementById('combined_fecha_inicio');

        const fecha = fechaInput.value;
        const hora = horaInput.value;

        if (fecha && hora) {

            combinedFechaInput.value = `${fecha} ${hora}:00`;
            event.target.submit();

        } else {
            alert('Por favor, selecciona una fecha y una hora.');
        }

        return false;
       }
    </script>

@endsection
