<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactoSolicitado;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Contacto",
    type: "object",
    required: ["empresa_id","persona_id","estado"],
    properties: [
        new OA\Property(property: "id", type: "string"),
        new OA\Property(property: "empresa_id", type: "string"),
        new OA\Property(property: "persona_id", type: "string"),
        new OA\Property(property: "estado", type: "string", example: "pendiente"),
        new OA\Property(property: "notas_admin", type: "string")
    ]
)]

#[OA\Tag(name: "Contactos")]
class ContactoController extends Controller
{
    #[OA\Get(
        path: "/api/contactos",
        tags: ["Contactos"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Listado",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Contacto")
                )
            )
        ]
    )]
    public function index()
    {
        return response()->json(
            ContactoSolicitado::with(['empresa','persona'])->get(),
            200
        );
    }

    #[OA\Post(
        path: "/api/contactos",
        tags: ["Contactos"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/Contacto")
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Contacto creado",
                content: new OA\JsonContent(ref: "#/components/schemas/Contacto")
            )
        ]
    )]
    public function store(Request $request)
    {
        $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'persona_id' => 'required|exists:personas,id',
            'estado' => 'required'
        ]);

        $contacto = ContactoSolicitado::create($request->all());

        return response()->json($contacto, 201);
    }

    public function show($id)
    {
        return response()->json(
            ContactoSolicitado::with(['empresa','persona'])->findOrFail($id),
            200
        );
    }

    public function update(Request $request, $id)
    {
        $contacto = ContactoSolicitado::findOrFail($id);
        $contacto->update($request->all());

        return response()->json($contacto, 200);
    }

    public function destroy($id)
    {
        $contacto = ContactoSolicitado::findOrFail($id);
        $contacto->delete();

        return response()->json([
            'message' => 'Contacto eliminado'
        ], 200);
    }
}
