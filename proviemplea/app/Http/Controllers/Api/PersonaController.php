<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Persona",
    type: "object",
    required: ["email","telefono"],
    properties: [
        new OA\Property(property: "id", type: "string", example: "550e8400-e29b-41d4-a716-446655440000"),
        new OA\Property(property: "email", type: "string", example: "juan@email.com"),
        new OA\Property(property: "telefono", type: "string", example: "987654321"),
        new OA\Property(property: "codigo_talento", type: "string", example: "TAL-001"),
        new OA\Property(property: "nivel_educacional", type: "string", example: "Universitario"),
        new OA\Property(property: "titulo_carrera", type: "string", example: "Ingeniería Informática"),
        new OA\Property(property: "anios_experiencia", type: "integer", example: 3),
        new OA\Property(property: "competencias", type: "array", items: new OA\Items(type: "string")),
        new OA\Property(property: "tipo_jornada", type: "string", example: "Completa"),
        new OA\Property(property: "modalidad", type: "string", example: "Remoto"),
        new OA\Property(property: "validado", type: "boolean", example: false),
        new OA\Property(property: "activo", type: "boolean", example: true)
    ]
)]

#[OA\Tag(name: "Personas", description: "Gestión de talentos")]
class PersonaController extends Controller
{
    #[OA\Get(
        path: "/api/personas",
        summary: "Listar personas activas",
        tags: ["Personas"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Listado correcto",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Persona")
                )
            )
        ]
    )]
    public function index()
    {
        return response()->json(
            Persona::where('activo', true)->get(),
            200
        );
    }

    #[OA\Post(
        path: "/api/personas",
        summary: "Crear persona",
        tags: ["Personas"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/Persona")
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Persona creada",
                content: new OA\JsonContent(ref: "#/components/schemas/Persona")
            ),
            new OA\Response(response: 422, description: "Error de validación")
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:personas,email',
            'telefono' => 'required'
        ]);

        $persona = Persona::create($request->all());

        return response()->json($persona, 201);
    }

    #[OA\Get(
        path: "/api/personas/{id}",
        summary: "Obtener persona por ID",
        tags: ["Personas"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Persona encontrada",
                content: new OA\JsonContent(ref: "#/components/schemas/Persona")
            ),
            new OA\Response(response: 404, description: "No encontrada")
        ]
    )]
    public function show($id)
    {
        return response()->json(
            Persona::findOrFail($id),
            200
        );
    }

    public function update(Request $request, $id)
    {
        $persona = Persona::findOrFail($id);

        $persona->update($request->all());

        return response()->json($persona, 200);
    }

    public function destroy($id)
    {
        $persona = Persona::findOrFail($id);

        $persona->update(['activo' => false]);

        return response()->json([
            'message' => 'Persona desactivada correctamente'
        ], 200);
    }
}
