<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\PersonaController;
use App\Http\Controllers\Api\EmpresaController;
use App\Http\Controllers\Api\ContactoController;

// =============================================
// RUTAS DE PERSONAS (Talentos)
// =============================================
Route::get('/personas',          [PersonaController::class, 'index']);   // Listar todas
Route::post('/personas',         [PersonaController::class, 'store']);   // Crear
Route::get('/personas/{id}',     [PersonaController::class, 'show']);    // Ver una
Route::put('/personas/{id}',     [PersonaController::class, 'update']);  // Actualizar completo
Route::patch('/personas/{id}/toggle', [PersonaController::class, 'toggle']); // Activar/desactivar
Route::delete('/personas/{id}',  [PersonaController::class, 'destroy']); // Eliminar

// =============================================
// RUTAS DE EMPRESAS
// =============================================
Route::get('/empresas',          [EmpresaController::class, 'index']);
Route::post('/empresas',         [EmpresaController::class, 'store']);
Route::get('/empresas/{id}',     [EmpresaController::class, 'show']);
Route::put('/empresas/{id}',     [EmpresaController::class, 'update']);
Route::patch('/empresas/{id}/toggle', [EmpresaController::class, 'toggle']);
Route::delete('/empresas/{id}',  [EmpresaController::class, 'destroy']);

// =============================================
// RUTAS DE CONTACTOS
// =============================================
Route::get('/contactos',         [ContactoController::class, 'index']);
Route::post('/contactos',        [ContactoController::class, 'store']);
Route::get('/contactos/{id}',    [ContactoController::class, 'show']);
Route::put('/contactos/{id}',    [ContactoController::class, 'update']);
Route::delete('/contactos/{id}', [ContactoController::class, 'destroy']);
