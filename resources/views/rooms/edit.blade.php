@extends('home')

@section('dashboard-content')

<div class="card shadow-lg border-0" style="border-radius: 15px; background-color: #1e1e1e;">
    <div class="card-header text-center bg-dark text-white" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
        <h4 class="mb-0">Modificar Sala: {{ $room->id }}</h4>
    </div>
    <div class="card-body text-white">

        <!-- Formulario para modificar la sala -->
        <form action="{{ route('rooms.update', $room->id) }}" method="POST">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre de la Sala</label>
                <input type="text" class="form-control bg-dark text-white border-0" id="nombre" name="nombre" value="{{ old('name', $room->nombre) }}" required placeholder="Introduce el nombre de la sala">
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control bg-dark text-white border-0" id="descripcion" name="descripcion" placeholder="Introduce una descripción de la sala">{{ old('descripcion', $room->descripcion) }}</textarea>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary w-100" style="background-color: #007bff;">Guardar Cambios</button>
            </div>
        </form>

        <div class="mt-3 text-center">
            <a href="{{ route('rooms.index') }}" class="btn btn-secondary btn-lg">Volver</a>
        </div>

    </div>
</div>

@endsection
