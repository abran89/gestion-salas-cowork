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
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('rooms.index') }}">Salas</a>
                        </li>
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
