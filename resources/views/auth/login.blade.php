@extends('layouts.app')

@section('content')

    <div class="container-fluid d-flex justify-content-center align-items-center" style="height: 100vh; background-color: #121212;">
        <div class="row justify-content-center w-100">
            <div class="col-md-6 col-lg-4">

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card shadow-lg border-0" style="border-radius: 15px; background-color: #1e1e1e;">
                    <div class="card-header text-center bg-dark text-white" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                        <h4 class="mb-0">Iniciar sesión</h4>
                    </div>
                    <div class="card-body text-white">
                        @guest

                            <form action="{{ route('login') }}" method="POST">
                                @csrf

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
                                    <label for="email" class="form-label">Correo electrónico</label>
                                    <input type="email" class="form-control bg-dark text-white border-0" id="email" name="email" required placeholder="Introduce tu correo electrónico">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control bg-dark text-white border-0" id="password" name="password" required placeholder="Introduce tu contraseña">
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary w-100" style="background-color: #007bff;">Iniciar sesión</button>
                                </div>
                            </form>


                            <div class="mt-3 text-center">
                                <p>¿No tienes cuenta? <a href="{{ route('register') }}" style="color: #007bff;">Regístrate aquí</a></p>
                            </div>
                        @endguest

                        @auth
                            <div class="text-center">
                                <p class="text-success">¡Estás autenticado!</p>
                                <a href="{{ route('dashboard') }}" class="btn btn-success btn-lg">Ir al menu</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
