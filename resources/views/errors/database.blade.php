@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-center align-items-center" style="height: 100vh; background-color: #121212;">
    <div class="row justify-content-center w-100">
        <div class="col-md-6 col-lg-4  text-white">
            <h1>¡Ups!</h1>
            <p>Hubo un problema al conectarnos con la base de datos.</p>
            <p>Por favor, inténtelo nuevamente más tarde o contacte al administrador.</p>
        </div>
    </div>
</div>
@endsection
