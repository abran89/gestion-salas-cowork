<!-- resources/views/dashboard.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container-fluid d-flex flex-column" style="background-color: #121212; min-height: 100vh;">

       <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="border-radius: 0 0 15px 15px;">
            <div class="container">
                <a class="navbar-brand" href="#">Cowork</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">

                        <!-- Dropdown visible solo para administradores -->
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
                        @endauth

                        <!-- Nombre del usuario autenticado -->
                        @auth
                            <li class="nav-item">
                                <span class="nav-link text-white">Bienvenido, {{ Auth::user()->name }}</span>
                            </li>
                        @endauth

                        <!-- Opción de cerrar sesión -->
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

        <!-- Contenido del Dashboard -->
        <div class="container text-white mt-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h1 class="display-4 text-center">Bienvenido al Dashboard</h1>
                    <p class="lead text-center">
                        ¡Aquí puedes gestionar todas las salas de coworking y mucho más!
                    </p>

                    <div class="text-center">
                        <a href="{{ route('rooms.index') }}" class="btn btn-primary btn-lg" style="background-color: #007bff;">Ver Salas</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
