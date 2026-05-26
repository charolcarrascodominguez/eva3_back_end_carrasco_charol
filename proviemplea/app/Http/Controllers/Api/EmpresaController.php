<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Empresa",
    type: "object",
    required: ["nombre_empresa","rut_empresa"],
    properties: [
        new OA\Property(property: "id", type: "string"),
        new OA\Property(property: "nombre_empresa", type: "string", example: "NTT DATA"),
        new OA\Property(property: "rut_empresa", type: "string", example: "76.123.456-7"),
        new OA\Property(property: "email", type: "string", example: "contacto@empresa.cl"),
        new OA\Property(property: "tipo_empresa", type: "string"),
        new OA\Property(property: "rubro", type: "string"),
        new OA\Property(property: "beneficios", type: "array", items: new OA\Items(type: "string")),
        new OA\Property(property: "validado", type: "boolean"),
        new OA\Property(property: "activo", type: "boolean")
    ]
)]

#[OA\Tag(name: "Empresas")]
class EmpresaController extends Controller
{
    #[OA\Get(
        path: "/api/empresas",
        tags: ["Empresas"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Listado",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Empresa")
                )
            )
        ]
    )]
    public function index()
    {
        return response()->json(
            Empresa::where('activo', true)->get(),
            200
        );
    }

    #[OA\Post(
        path: "/api/empresas",
        tags: ["Empresas"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/Empresa")
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Empresa creada",
                content: new OA\JsonContent(ref: "#/components/schemas/Empresa")
            )
        ]
    )]
    public function store(Request $request)
    {
        $request->validate([
            'nombre_empresa' => 'required',
            'rut_empresa' => 'required|unique:empresas,rut_empresa'
        ]);

        $empresa = Empresa::create($request->all());

        return response()->json($empresa, 201);
    }

    public function show($id)
    {
        return response()->json(Empresa::findOrFail($id), 200);
    }

    public function update(Request $request, $id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->update($request->all());

        return response()->json($empresa, 200);
    }

    public function destroy($id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->update(['activo' => false]);

        return response()->json([
            'message' => 'Empresa desactivada'
        ], 200);
    }
}
