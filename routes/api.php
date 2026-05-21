<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\ComprobarToken;
use Illuminate\Support\Facades\Route;

// Rutas publicas (sin necesidad de estar logeado)
Route::post('/login', [AuthController::class, 'login']);
Route::get('/estado', [AuthController::class, 'estado']);

// Rutas protegidas (requieren token valido)
Route::middleware(ComprobarToken::class)->group(function () {
    Route::get('/perfil', [AuthController::class, 'perfil']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
