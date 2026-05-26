<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "API ProviEmplea",
    description: "Documentación oficial de la API ProviEmplea"
)]

#[OA\Server(
    url: "http://localhost:8081",
    description: "Servidor Local Docker"
)]

class SwaggerController extends Controller
{
}
