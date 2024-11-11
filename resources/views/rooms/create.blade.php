@extends('home')

@section('dashboard-content')

    <div class="card shadow-lg border-0 col-md-6" style="border-radius: 15px; background-color: #1e1e1e;">
        <div class="card-header text-center bg-dark text-white" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
            <h4 class="mb-0">Crear Sala</h4>
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

            <form action="{{ route('rooms.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de la Sala</label>
                    <input type="text" class="form-control bg-dark text-white border-0" id="room_name" name="nombre" required placeholder="Introduce el nombre de la sala">
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control bg-dark text-white border-0" id="room_description" name="descripcion" rows="3" placeholder="Describe brevemente la sala"></textarea>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary w-100" style="background-color: #007bff;">Crear Sala</button>
                </div>
            </form>
        </div>
    </div>

@endsection
