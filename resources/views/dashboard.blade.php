@extends('home')

@section('dashboard-content')
<div class="container text-white mt-4">
    <div class="row justify-content-center">
        @auth
            @if(Auth::user()->is_admin)
                <div class="col-12 text-center">
                    <div class="card bg-dark text-white">
                        <div class="card-body">
                            <h5 class="card-title">Bienvenido Administrador</h5>
                            <p class="card-text">Como administrador, puedes gestionar las salas y supervisar las reservas.</p>
                            <a href="{{ route('rooms.index') }}" class="btn btn-primary">Gestionar Salas</a>
                            <a href="{{ route('reservations.index') }}" class="btn btn-success">Gestionar Reservas</a>
                        </div>
                    </div>
                </div>
            @else

                <div class="col-12 text-center">
                    <div class="card bg-dark text-white">
                        <div class="card-body">
                            <h5 class="card-title">Bienvenido</h5>
                            <p class="card-text">Puedes generar tus propias reservas para las salas de coworking.</p>
                            <a href="{{ route('reservations.create') }}" class="btn btn-primary">Crear Reserva</a>
                            <a href="{{ route('reservations.index') }}" class="btn btn-success">Ver Mis Reservas</a>
                        </div>
                    </div>
                </div>
            @endif
        @endauth
    </div>
</div>
@endsection
