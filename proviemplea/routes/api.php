<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\PersonaController;
use App\Http\Controllers\Api\EmpresaController;
use App\Http\Controllers\Api\ContactoController;

Route::apiResource('personas', PersonaController::class);
Route::apiResource('empresas', EmpresaController::class);
Route::apiResource('contactos', ContactoController::class);
