<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactoSolicitado;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Contacto",
    type: "object",
    required: ["empresa_id", "persona_id", "estado"],
    properties: [
        new OA\Property(property: "id",          type: "string", format: "uuid", example: "772a1622-a40d-53e6-c928-668877662222"),
        new OA\Property(property: "empresa_id",  type: "string", format: "uuid", example: "661f9511-f39c-42d5-b817-557766551111"),
        new OA\Property(property: "persona_id",  type: "string", format: "uuid", example: "550e8400-e29b-41d4-a716-446655440000"),
        new OA\Property(property: "estado",      type: "string",                 example: "pendiente"),
        new OA\Property(property: "notas_admin", type: "string",                 example: "Empresa verificada, contacto válido")
    ]
)]
#[OA\Tag(name: "Contactos", description: "Gestión de solicitudes de contacto entre empresas y talentos")]
class ContactoController extends Controller
{
    #[OA\Get(
        path: "/api/contactos",
        summary: "Listar solicitudes de contacto",
        description: "Retorna todas las solicitudes de contacto con datos de empresa y persona relacionadas.",
        tags: ["Contactos"],
        parameters: [
            new OA\Parameter(name: "estado", in: "query", required: false, description: "Filtrar por estado: pendiente, aceptado, rechazado", schema: new OA\Schema(type: "string", example: "pendiente"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Listado obtenido correctamente",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Contacto"),
                    example: [[
                        "id" => "772a1622-a40d-53e6-c928-668877662222",
                        "empresa_id" => "661f9511-f39c-42d5-b817-557766551111",
                        "persona_id" => "550e8400-e29b-41d4-a716-446655440000",
                        "estado" => "pendiente",
                        "notas_admin" => null
                    ]]
                )
            ),
            new OA\Response(response: 500, description: "Error interno del servidor")
        ]
    )]
    public function index()
    {
        return response()->json(
            ContactoSolicitado::with(['empresa', 'persona'])->get(),
            200
        );
    }

    #[OA\Post(
        path: "/api/contactos",
        summary: "Crear solicitud de contacto",
        description: "Una empresa solicita contactar a un talento. El estado inicial es pendiente.",
        tags: ["Contactos"],
        requestBody: new OA\RequestBody(
            required: true,
            description: "Datos de la solicitud de contacto",
            content: new OA\JsonContent(
                ref: "#/components/schemas/Contacto",
                example: [
                    "empresa_id" => "661f9511-f39c-42d5-b817-557766551111",
                    "persona_id" => "550e8400-e29b-41d4-a716-446655440000",
                    "estado" => "pendiente",
                    "notas_admin" => "Contacto iniciado desde búsqueda por rubro Tecnología"
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Solicitud creada exitosamente",      content: new OA\JsonContent(ref: "#/components/schemas/Contacto")),
            new OA\Response(response: 422, description: "Error de validación — empresa_id o persona_id no existen"),
            new OA\Response(response: 500, description: "Error interno del servidor")
        ]
    )]
    public function store(Request $request)
    {
        $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'persona_id' => 'required|exists:personas,id',
            'estado'     => 'required'
        ]);
        return response()->json(ContactoSolicitado::create($request->all()), 201);
    }

    #[OA\Get(
        path: "/api/contactos/{id}",
        summary: "Obtener solicitud de contacto por ID",
        description: "Retorna los datos de una solicitud junto con la empresa y talento relacionados.",
        tags: ["Contactos"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, description: "UUID del contacto", schema: new OA\Schema(type: "string", format: "uuid", example: "772a1622-a40d-53e6-c928-668877662222"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Contacto encontrado",     content: new OA\JsonContent(ref: "#/components/schemas/Contacto")),
            new OA\Response(response: 404, description: "Contacto no encontrado"),
            new OA\Response(response: 500, description: "Error interno del servidor")
        ]
    )]
    public function show($id)
    {
        return response()->json(
            ContactoSolicitado::with(['empresa', 'persona'])->findOrFail($id),
            200
        );
    }

    #[OA\Put(
        path: "/api/contactos/{id}",
        summary: "Actualizar solicitud de contacto (PUT)",
        description: "Permite actualizar el estado o las notas de una solicitud existente.",
        tags: ["Contactos"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, description: "UUID del contacto", schema: new OA\Schema(type: "string", format: "uuid", example: "772a1622-a40d-53e6-c928-668877662222"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: "#/components/schemas/Contacto",
                example: [
                    "estado" => "aceptado",
                    "notas_admin" => "Empresa verificada y talento notificado"
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Contacto actualizado correctamente", content: new OA\JsonContent(ref: "#/components/schemas/Contacto")),
            new OA\Response(response: 404, description: "Contacto no encontrado"),
            new OA\Response(response: 422, description: "Error de validación"),
            new OA\Response(response: 500, description: "Error interno del servidor")
        ]
    )]
    public function update(Request $request, $id)
    {
        $contacto = ContactoSolicitado::findOrFail($id);
        $contacto->update($request->all());
        return response()->json($contacto, 200);
    }

    #[OA\Delete(
        path: "/api/contactos/{id}",
        summary: "Eliminar solicitud de contacto",
        description: "Elimina definitivamente una solicitud de contacto de la base de datos.",
        tags: ["Contactos"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, description: "UUID del contacto", schema: new OA\Schema(type: "string", format: "uuid", example: "772a1622-a40d-53e6-c928-668877662222"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Contacto eliminado correctamente",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Contacto eliminado")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Contacto no encontrado"),
            new OA\Response(response: 500, description: "Error interno del servidor")
        ]
    )]
    public function destroy($id)
    {
        $contacto = ContactoSolicitado::findOrFail($id);
        $contacto->delete();
        return response()->json(['message' => 'Contacto eliminado'], 200);
    }
}
