
<?php

use App\Controllers\ChambreController as ControllersChambreController;
use App\Controllers\ClientController as ControllersClientController;
use App\Controllers\ReservationController as ControllersReservationController;
use Illuminate\Support\Facades\Route;


Route::get('/clients', [ControllersClientController::class, 'index']);
Route::get('/clients/{id}/voir', [ControllersClientController::class, 'show']);
Route::post('/clients/ajouter', [ControllersClientController::class, 'store']);
Route::put('/clients/{id}/modifier', [ControllersClientController::class, 'update']);
Route::delete('/clients/{id}/supprimer', [ControllersClientController::class, 'destroy']);

Route::get('/chambres', [ControllersChambreController::class, 'index']);
Route::get('/chambres/{id}/voir', [ControllersChambreController::class, 'show']);
Route::get('/chambres/disponibles', [ControllersChambreController::class, 'available']);
Route::post('/chambres/ajouter', [ControllersChambreController::class, 'store']);
Route::put('/chambres/{id}/modifier', [ControllersChambreController::class, 'update']);
Route::delete('/chambres/{id}/supprimer', [ControllersChambreController::class, 'destroy']);

Route::get('/reservations', [ControllersReservationController::class, 'index']);
Route::get('/reservations/{id}/voir', [ControllersReservationController::class, 'show']);
Route::post('/reservations/ajouter', [ControllersReservationController::class, 'store']);
Route::put('/reservations/{id}/modifier', [ControllersReservationController::class, 'update']);
Route::delete('/reservations/{id}/supprimer', [ControllersReservationController::class, 'destroy']);
