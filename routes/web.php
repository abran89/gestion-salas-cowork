<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ReservationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
*/

// Ruta de inicio / páginas de autenticación
Route::get('/', function () {
    return view('auth/login');
})->name('home');

Route::get('/login', function () {
    return view('auth/login');
});

Route::get('/register', function () {
    return view('auth/register');
})->name('register');

Route::post('register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login'])->name('login');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {

    // Página de inicio después de autenticarse
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    // Ruta para cerrar sesión
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Rutas para la gestión de reservas
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
});

// Rutas protegidas por autenticación y middleware de administrador
Route::middleware(['auth','admin'])->group(function () {

    // Gestión de salas
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
    Route::put('/rooms/update/{room}', [RoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/delete/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');

    // Actualización del estado de la reserva
    Route::patch('/reservations/{reservation}/update-status', [ReservationController::class, 'updateStatus'])->name('reservations.updateStatus');
});
