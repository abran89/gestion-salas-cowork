@extends('layouts.app')

@section('content')
    <div class="container-fluid d-flex flex-column" style="background-color: #121212; min-height: 100vh;">

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="border-radius: 0 0 15px 15px;">
            <div class="container">
                <a class="navbar-brand" href="{{ route('dashboard') }}">Cowork</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">

                        @auth
                            @if(Auth::user()->is_admin)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="salasDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Salas
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="salasDropdown">
                                        <li><a class="dropdown-item" href="{{ route('rooms.index') }}">Ver Salas</a></li>
                                        <li><a class="dropdown-item" href="{{ route('rooms.create') }}">Crear Sala</a></li>
                                    </ul>
                                </li>
                            @endif

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="reservasDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Reservas
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="reservasDropdown">
                                    <li><a class="dropdown-item" href="{{ route('reservations.create') }}">Crear Reserva</a></li>
                                    <li><a class="dropdown-item" href="{{ route('reservations.index') }}">Listar Reservas</a></li>
                                </ul>
                            </li>
                        @endauth

                        @auth
                            <li class="nav-item">
                                <span class="nav-link text-white">Bienvenido, {{ Auth::user()->name }}</span>
                            </li>
                        @endauth

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar sesión</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>


        <div class="container text-white mt-4">
            <div class="row justify-content-center">
                @yield('dashboard-content')
            </div>
        </div>

    </div>
@endsection
