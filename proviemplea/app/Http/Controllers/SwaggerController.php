<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "API ProviEmplea",
    description: "Documentación oficial de la API ProviEmplea.

Rate Limiting: Máximo 60 solicitudes por minuto por IP.
Caché: Los endpoints GET tienen caché de 5 minutos.
Tiempos de respuesta esperados:
GET ~100ms | POST ~200ms | PUT ~150ms | DELETE ~100ms."
)]

#[OA\Server(
    url: "http://localhost:8081",
    description: "Servidor local de desarrollo"
)]

class SwaggerController extends Controller
{
}
