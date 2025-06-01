
<?php
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ChambreController;
use App\Http\Controllers\ReservationController;

Route::get('/clients', [ClientController::class, 'index']);
Route::get('/clients/{id}/voir', [ClientController::class, 'show']);
Route::post('/clients/ajouter', [ClientController::class, 'store']);
Route::put('/clients/{id}/modifier', [ClientController::class, 'update']);
Route::delete('/clients/{id}/supprimer', [ClientController::class, 'destroy']);

Route::get('/chambres', [ChambreController::class, 'index']);
Route::get('/chambres/{id}/voir', [ChambreController::class, 'show']);
Route::get('/chambres/disponibles', [ChambreController::class, 'available']);
Route::post('/chambres/ajouter', [ChambreController::class, 'store']);
Route::put('/chambres/{id}/modifier', [ChambreController::class, 'update']);
Route::delete('/chambres/{id}/supprimer', [ChambreController::class, 'destroy']);

Route::get('/reservations', [ReservationController::class, 'index']);
Route::get('/reservations/{id}/voir', [ReservationController::class, 'show']);
Route::post('/reservations/ajouter', [ReservationController::class, 'store']);
Route::put('/reservations/{id}/modifier', [ReservationController::class, 'update']);
Route::delete('/reservations/{id}/supprimer', [ReservationController::class, 'destroy']);
