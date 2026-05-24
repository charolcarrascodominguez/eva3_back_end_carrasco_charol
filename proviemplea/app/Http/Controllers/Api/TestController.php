<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class TestController extends Controller
{
    #[OA\Get(
        path: "/api/test",
        summary: "Endpoint de prueba",
        tags: ["Test"],
        responses: [
            new OA\Response(
                response: 200,
                description: "OK"
            )
        ]
    )]
    public function index(): JsonResponse
    {
        return response()->json([
            'message' => 'Swagger funcionando'
        ]);
    }
}
