<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Persona",
    type: "object",
    required: ["email", "telefono"],
    properties: [
        new OA\Property(property: "id",                type: "string",  format: "uuid",    example: "550e8400-e29b-41d4-a716-446655440000"),
        new OA\Property(property: "email",             type: "string",  format: "email",   example: "talento01@email.com"),
        new OA\Property(property: "telefono",          type: "string",                     example: "987654321"),
        new OA\Property(property: "codigo_talento",    type: "string",                     example: "TAL-001"),
        new OA\Property(property: "nivel_educacional", type: "string",                     example: "Universitario"),
        new OA\Property(property: "titulo_carrera",    type: "string",                     example: "Ingeniería Informática"),
        new OA\Property(property: "anios_experiencia", type: "integer",                    example: 3),
        new OA\Property(property: "competencias",      type: "array",   items: new OA\Items(type: "string", example: "PHP")),
        new OA\Property(property: "tipo_jornada",      type: "string",                     example: "Completa"),
        new OA\Property(property: "modalidad",         type: "string",                     example: "Remoto"),
        new OA\Property(property: "validado",          type: "boolean",                    example: false),
        new OA\Property(property: "activo",            type: "boolean",                    example: true)
    ]
)]
#[OA\Tag(name: "Personas", description: "Gestión de talentos de la plataforma ProviEmplea")]
class PersonaController extends Controller
{
    #[OA\Get(
        path: "/api/personas",
        summary: "Listar personas activas",
        description: "Retorna el listado de personas/talentos con estado activo.",
        tags: ["Personas"],
        parameters: [
            new OA\Parameter(name: "modalidad",    in: "query", required: false, description: "Filtrar por modalidad: Presencial, Remoto, Híbrido",          schema: new OA\Schema(type: "string", example: "Remoto")),
            new OA\Parameter(name: "tipo_jornada", in: "query", required: false, description: "Filtrar por jornada: Completa, Part-time, Por proyecto", schema: new OA\Schema(type: "string", example: "Completa"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Listado obtenido correctamente",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Persona"),
                    example: [[
                        "id" => "550e8400-e29b-41d4-a716-446655440000",
                        "email" => "talento01@email.com",
                        "telefono" => "987654321",
                        "codigo_talento" => "TAL-001",
                        "nivel_educacional" => "Universitario",
                        "titulo_carrera" => "Ingeniería Informática",
                        "anios_experiencia" => 3,
                        "competencias" => ["PHP", "Laravel", "MySQL"],
                        "tipo_jornada" => "Completa",
                        "modalidad" => "Remoto",
                        "validado" => false,
                        "activo" => true
                    ]]
                )
            ),
            new OA\Response(response: 500, description: "Error interno del servidor")
        ]
    )]
    public function index()
    {
        return response()->json(Persona::where('activo', true)->get(), 200);
    }

    #[OA\Post(
        path: "/api/personas",
        summary: "Crear nueva persona/talento",
        description: "Registra un nuevo talento. Solo se requieren email y teléfono.",
        tags: ["Personas"],
        requestBody: new OA\RequestBody(
            required: true,
            description: "Datos del talento a registrar",
            content: new OA\JsonContent(
                ref: "#/components/schemas/Persona",
                example: [
                    "email" => "talento02@email.com",
                    "telefono" => "912345678",
                    "codigo_talento" => "TAL-002",
                    "nivel_educacional" => "Técnico",
                    "titulo_carrera" => "Técnico en Redes",
                    "anios_experiencia" => 2,
                    "competencias" => ["Redes", "Linux", "Soporte TI"],
                    "tipo_jornada" => "Part-time",
                    "modalidad" => "Híbrido"
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Persona creada exitosamente",        content: new OA\JsonContent(ref: "#/components/schemas/Persona")),
            new OA\Response(response: 422, description: "Error de validación"),
            new OA\Response(response: 500, description: "Error interno del servidor")
        ]
    )]
    public function store(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|unique:personas,email',
            'telefono' => 'required'
        ]);
        return response()->json(Persona::create($request->all()), 201);
    }

    #[OA\Get(
        path: "/api/personas/{id}",
        summary: "Obtener persona por ID",
        description: "Retorna los datos de una persona usando su UUID.",
        tags: ["Personas"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, description: "UUID de la persona", schema: new OA\Schema(type: "string", format: "uuid", example: "550e8400-e29b-41d4-a716-446655440000"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Persona encontrada",     content: new OA\JsonContent(ref: "#/components/schemas/Persona")),
            new OA\Response(response: 404, description: "Persona no encontrada"),
            new OA\Response(response: 500, description: "Error interno del servidor")
        ]
    )]
    public function show($id)
    {
        return response()->json(Persona::findOrFail($id), 200);
    }

    #[OA\Put(
        path: "/api/personas/{id}",
        summary: "Actualizar persona completa (PUT)",
        description: "Actualiza todos los campos de una persona existente.",
        tags: ["Personas"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, description: "UUID de la persona", schema: new OA\Schema(type: "string", format: "uuid", example: "550e8400-e29b-41d4-a716-446655440000"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: "#/components/schemas/Persona",
                example: [
                    "email" => "actualizado@email.com",
                    "telefono" => "911111111",
                    "nivel_educacional" => "Postgrado",
                    "titulo_carrera" => "Magíster en Ciencias de Datos",
                    "anios_experiencia" => 6,
                    "competencias" => ["Python", "Machine Learning", "SQL"],
                    "tipo_jornada" => "Completa",
                    "modalidad" => "Remoto"
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Persona actualizada correctamente", content: new OA\JsonContent(ref: "#/components/schemas/Persona")),
            new OA\Response(response: 404, description: "Persona no encontrada"),
            new OA\Response(response: 422, description: "Error de validación"),
            new OA\Response(response: 500, description: "Error interno del servidor")
        ]
    )]
    public function update(Request $request, $id)
    {
        $persona = Persona::findOrFail($id);
        $persona->update($request->all());
        return response()->json($persona, 200);
    }

    #[OA\Patch(
        path: "/api/personas/{id}/toggle",
        summary: "Activar o desactivar perfil (PATCH)",
        description: "Cambia el estado activo/inactivo de una persona. Operación de encender o apagar.",
        tags: ["Personas"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, description: "UUID de la persona", schema: new OA\Schema(type: "string", format: "uuid", example: "550e8400-e29b-41d4-a716-446655440000"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Estado cambiado correctamente",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string",  example: "Estado actualizado correctamente"),
                        new OA\Property(property: "activo",  type: "boolean", example: false)
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Persona no encontrada"),
            new OA\Response(response: 500, description: "Error interno del servidor")
        ]
    )]
    public function toggle($id)
    {
        $persona = Persona::findOrFail($id);
        $persona->update(['activo' => !$persona->activo]);
        return response()->json(['message' => 'Estado actualizado correctamente', 'activo' => $persona->activo], 200);
    }

    #[OA\Delete(
        path: "/api/personas/{id}",
        summary: "Eliminar persona",
        description: "Borrado lógico: pone activo=false. La persona no aparece en listados pero sus datos se conservan.",
        tags: ["Personas"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, description: "UUID de la persona", schema: new OA\Schema(type: "string", format: "uuid", example: "550e8400-e29b-41d4-a716-446655440000"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Persona eliminada correctamente",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Persona desactivada correctamente")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Persona no encontrada"),
            new OA\Response(response: 500, description: "Error interno del servidor")
        ]
    )]
    public function destroy($id)
    {
        $persona = Persona::findOrFail($id);
        $persona->update(['activo' => false]);
        return response()->json(['message' => 'Persona desactivada correctamente'], 200);
    }
}
