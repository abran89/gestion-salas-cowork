@extends('layouts.app')

@section('content')

    <div class="container-fluid d-flex justify-content-center align-items-center" style="height: 100vh; background-color: #121212;">
        <div class="row justify-content-center w-100">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-lg border-0" style="border-radius: 15px; background-color: #1e1e1e;">
                    <div class="card-header text-center bg-dark text-white" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                        <h4 class="mb-0">Registro de cuenta</h4>
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


                        <form action="{{ route('register') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre</label>
                                <input type="text" class="form-control bg-dark text-white border-0" id="name" name="name" value="{{ old('name') }}" required placeholder="Introduce tu nombre">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control bg-dark text-white border-0" id="email" name="email" value="{{ old('email') }}" required placeholder="Introduce tu correo electrónico">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control bg-dark text-white border-0" id="password" name="password" required placeholder="Introduce tu contraseña">
                                <small class="form-text text-muted">La contraseña debe tener al menos 8 caracteres.</small>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                                <input type="password" class="form-control bg-dark text-white border-0" id="password_confirmation" name="password_confirmation" required placeholder="Confirma tu contraseña">
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary w-100" style="background-color: #007bff;">Registrar cuenta</button>
                            </div>
                        </form>
                        <div class="mt-3 text-center">
                            <p>¿Ya tienes cuenta? <a href="{{ route('home') }}" style="color: #007bff;">Inicia sesión aquí</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
