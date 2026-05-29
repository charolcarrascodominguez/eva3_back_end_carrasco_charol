<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Empresa",
    type: "object",
    required: ["nombre_empresa", "rut_empresa"],
    properties: [
        new OA\Property(property: "id",             type: "string",  format: "uuid",  example: "661f9511-f39c-42d5-b817-557766551111"),
        new OA\Property(property: "nombre_empresa", type: "string",                   example: "NTT DATA Chile"),
        new OA\Property(property: "rut_empresa",    type: "string",                   example: "76.123.456-7"),
        new OA\Property(property: "email",          type: "string",  format: "email", example: "contacto@nttdata.cl"),
        new OA\Property(property: "tipo_empresa",   type: "string",                   example: "Privada"),
        new OA\Property(property: "rubro",          type: "string",                   example: "Tecnología"),
        new OA\Property(property: "beneficios",     type: "array",   items: new OA\Items(type: "string", example: "Seguro de salud")),
        new OA\Property(property: "validado",       type: "boolean",                  example: false),
        new OA\Property(property: "activo",         type: "boolean",                  example: true)
    ]
)]
#[OA\Tag(name: "Empresas", description: "Gestión de empresas que buscan talentos en ProviEmplea")]
class EmpresaController extends Controller
{
    #[OA\Get(
        path: "/api/empresas",
        summary: "Listar empresas activas",
        description: "Retorna el listado de todas las empresas con estado activo.",
        tags: ["Empresas"],
        parameters: [
            new OA\Parameter(name: "rubro", in: "query", required: false, description: "Filtrar por rubro: Tecnología, Salud, Educación", schema: new OA\Schema(type: "string", example: "Tecnología"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Listado obtenido correctamente",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Empresa"),
                    example: [[
                        "id" => "661f9511-f39c-42d5-b817-557766551111",
                        "nombre_empresa" => "NTT DATA Chile",
                        "rut_empresa" => "76.123.456-7",
                        "email" => "contacto@nttdata.cl",
                        "tipo_empresa" => "Privada",
                        "rubro" => "Tecnología",
                        "beneficios" => ["Seguro de salud", "Home office", "Capacitaciones"],
                        "validado" => true,
                        "activo" => true
                    ]]
                )
            ),
            new OA\Response(response: 500, description: "Error interno del servidor")
        ]
    )]
    public function index()
    {
        return response()->json(Empresa::where('activo', true)->get(), 200);
    }

    #[OA\Post(
        path: "/api/empresas",
        summary: "Crear nueva empresa",
        description: "Registra una nueva empresa. El RUT debe ser único.",
        tags: ["Empresas"],
        requestBody: new OA\RequestBody(
            required: true,
            description: "Datos de la empresa a registrar",
            content: new OA\JsonContent(
                ref: "#/components/schemas/Empresa",
                example: [
                    "nombre_empresa" => "Startup Verde SpA",
                    "rut_empresa" => "77.987.654-3",
                    "email" => "hola@startupverde.cl",
                    "tipo_empresa" => "Startup",
                    "rubro" => "Sustentabilidad",
                    "beneficios" => ["Flexibilidad horaria", "Trabajo remoto", "Bono anual"]
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Empresa creada exitosamente",        content: new OA\JsonContent(ref: "#/components/schemas/Empresa")),
            new OA\Response(response: 422, description: "Error de validación — RUT duplicado"),
            new OA\Response(response: 500, description: "Error interno del servidor")
        ]
    )]
    public function store(Request $request)
    {
        $request->validate([
            'nombre_empresa' => 'required',
            'rut_empresa'    => 'required|unique:empresas,rut_empresa'
        ]);
        return response()->json(Empresa::create($request->all()), 201);
    }

    #[OA\Get(
        path: "/api/empresas/{id}",
        summary: "Obtener empresa por ID",
        description: "Retorna los datos de una empresa usando su UUID.",
        tags: ["Empresas"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, description: "UUID de la empresa", schema: new OA\Schema(type: "string", format: "uuid", example: "661f9511-f39c-42d5-b817-557766551111"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Empresa encontrada",     content: new OA\JsonContent(ref: "#/components/schemas/Empresa")),
            new OA\Response(response: 404, description: "Empresa no encontrada"),
            new OA\Response(response: 500, description: "Error interno del servidor")
        ]
    )]
    public function show($id)
    {
        return response()->json(Empresa::findOrFail($id), 200);
    }

    #[OA\Put(
        path: "/api/empresas/{id}",
        summary: "Actualizar empresa completa (PUT)",
        description: "Actualiza todos los datos de una empresa registrada.",
        tags: ["Empresas"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, description: "UUID de la empresa", schema: new OA\Schema(type: "string", format: "uuid", example: "661f9511-f39c-42d5-b817-557766551111"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: "#/components/schemas/Empresa",
                example: [
                    "nombre_empresa" => "NTT DATA Chile Actualizado",
                    "rut_empresa" => "76.123.456-7",
                    "email" => "nuevo@nttdata.cl",
                    "tipo_empresa" => "Privada",
                    "rubro" => "Tecnología e Innovación",
                    "beneficios" => ["Seguro de salud", "Home office", "Capacitaciones", "Bono anual"]
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Empresa actualizada correctamente", content: new OA\JsonContent(ref: "#/components/schemas/Empresa")),
            new OA\Response(response: 404, description: "Empresa no encontrada"),
            new OA\Response(response: 422, description: "Error de validación"),
            new OA\Response(response: 500, description: "Error interno del servidor")
        ]
    )]
    public function update(Request $request, $id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->update($request->all());
        return response()->json($empresa, 200);
    }

    #[OA\Patch(
        path: "/api/empresas/{id}/toggle",
        summary: "Activar o desactivar empresa (PATCH)",
        description: "Cambia el estado activo/inactivo de la empresa. Operación de encender o apagar.",
        tags: ["Empresas"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, description: "UUID de la empresa", schema: new OA\Schema(type: "string", format: "uuid", example: "661f9511-f39c-42d5-b817-557766551111"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Estado cambiado correctamente",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string",  example: "Estado de empresa actualizado correctamente"),
                        new OA\Property(property: "activo",  type: "boolean", example: false)
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Empresa no encontrada"),
            new OA\Response(response: 500, description: "Error interno del servidor")
        ]
    )]
    public function toggle($id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->update(['activo' => !$empresa->activo]);
        return response()->json(['message' => 'Estado de empresa actualizado correctamente', 'activo' => $empresa->activo], 200);
    }

    #[OA\Delete(
        path: "/api/empresas/{id}",
        summary: "Eliminar empresa",
        description: "Borrado lógico: pone activo=false. La empresa no aparece en listados pero sus datos se conservan.",
        tags: ["Empresas"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, description: "UUID de la empresa", schema: new OA\Schema(type: "string", format: "uuid", example: "661f9511-f39c-42d5-b817-557766551111"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Empresa eliminada correctamente",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Empresa desactivada")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Empresa no encontrada"),
            new OA\Response(response: 500, description: "Error interno del servidor")
        ]
    )]
    public function destroy($id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->update(['activo' => false]);
        return response()->json(['message' => 'Empresa desactivada'], 200);
    }
}
