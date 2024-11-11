@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-center align-items-center" style="height: 100vh; background-color: #121212;">
    <div class="row justify-content-center w-100">
        <div class="col-md-6 col-lg-4  text-white">
            <h1>¡Ups!</h1>
            <p>Hubo un problema al conectarnos con la base de datos.</p>
            @if (isset($errorDetails))
            <div class="alert alert-danger">
                <strong>Mensaje de Error:</strong> {{ $errorDetails['message'] }} <br>
                <strong>Detalles:</strong> {{ $errorDetails['error'] }} <br>
                <strong>Código de Error:</strong> {{ $errorDetails['code'] }}
            </div>
        @else
            <p>Ocurrió un error inesperado. Por favor, intente nuevamente más tarde.</p>
        @endif
        </div>
    </div>
</div>
@endsection
