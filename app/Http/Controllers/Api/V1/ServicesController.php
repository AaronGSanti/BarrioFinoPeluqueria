<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ServicesController extends Controller
{
    /**
     * @OA\Get(
     *  path="api/v1/services",
     *  tags={"Services"},
     *  summary="Get a list of all services",
     * 
     * @OA\Response(
     *  response=200,
     *  description= "Successfull operation",
     *
     * @OA\Response(
     *  response=404,
     *  description= "No servcices found")))
     */
    public function index(Request $request)
    {
        $services = Servicio::all();

        if ($services->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No services found'
            ], 404);
        } else {
            return response()->json([
                'status' => 'success',
                'data' => $services
            ], 200);
        }
    }

    /**
     * @OA\Post(
     *  path= "/api/v1/services/store",
     *  tags= {"Services"},
     *  summary= "Create a new service",
     * 
     * @OA\RequestBody(
     *  required= true,
     * @OA\JsonContent(
     *  required= {"nombre","precio","descripcion","duracion"},
     *  @OA\Property(property="nombre", type="string", example="Corte de cabello"),
     *  @OA\Property(property="precio", type="number", format="float", example=25.50),
     *  @OA\Property(property="descripcion", type="string", example="Corte de cabello para hombres y mujeres"),
     *  @OA\Property(property="duracion", type="integer", example=30)
     *  )
     * ),
     * @OA\Response(
     *  response=201,
     *  description= "Service created successfully"),
     * @OA\Response(
     *  response=422,
     *  description= "Validation failed")
     * )
     * 
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'precio' => 'required|numeric|min:0',
                'descripcion' => 'nullable|string',
                'duracion' => 'nullable|integer|min:0'
            ]);

            $service = Servicio::create($validatedData);

            return response()->json([
                'status' => 'success',
                'data' => $service
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * @OA\Put(
     *  path= "api/v1/services/put/{id}",
     *  tags= {"Services"},
     *  summary= "Update an existing service",
     * 
     * @OA\Parameter(
     *  name= "id",
     *  in= "path",
     *  required= true,
     *  description= "ID of the service to update",
     *  @OA\Schema(type= "integer" , example= 1)
     * ),
     * 
     * @OA\RequestBody(
     *  required= true,
     *  @OA\JsonContent(
     *  @OA\Property(property= "nombre", type= "string", example= "Corte de cabello actualizado"),
     *  @OA\Property(property= "precio", type= "number", format= "float", example= 30.00),
     *  @OA\Property(property= "descripcion", type= "string", example= "Corte de cabello actualizado para hombres y mujeres"),
     *  @OA\Property(property= "duracion", type= "integer", example= 45)
     *  )
     * ),
     * 
     * @OA\Response(
     *  response=200,
     *  description= "Service updated successfully"),
     * @OA\Response(
     *  response=404,
     *  description= "Service not found"),
     * @OA\Response(
     *  response=422,
     *  description= "Validation failed")
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'nombre' => 'sometimes|required|string|max:255',
                'precio' => 'sometimes|required|numeric|min:0',
                'descripcion' => 'sometimes|nullable|string',
                'duracion' => 'sometimes|nullable|integer|min:0'
            ]);

            $service = Servicio::find($id);

            if (!$service) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Service not found'
                ], 404);
            } else {
                $service->update($validatedData);

                return response()->json([
                    'status' => 'success',
                    'data' => $service
                ], 200);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * @OA\Delete(
     *  path= "api/v1/services/delete/{id}",
     *  tags= {"Services"},
     *  summary= "Delete a service",
     *  
     *  @OA\Parameter(
     *   name= "id",
     *   in= "path",
     *   required= true,
     *   description= "ID of the service to delete",
     *   @OA\Schema(type= "integer" , example= 1)
     * ),
     * @OA\Response(
     *  response=200,
     *  description= "Service deleted successfully"),
     * @OA\Response(
     *  response=404,
     *  description= "Service not found")
     * )
     */
    public function delete($id)
    {
        $service = Servicio::find($id);

        if (!$service) {
            return response()->json([
                'status' => 'error',
                'message' => 'Service not found'
            ], 404);
        } else {
            $service->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Service deleted successfully'
            ], 200);
        }
    }

    /**
     * @OA\Get(
     *  path= "api/v1/services/search/{nombre}",
     *  tags= { "Services" },
     *  summary= "Search services by nombre",
     * 
     * @OA\Parameter(
     *  name= "nombre",
     *  in= "path",
     *  required= true,
     *  description= "Nombre of the service to search for",
     *  @OA\Schema(type= "string" , example= "Corte")
     *  ),
     * @OA\Response(
     *  response=200,
     *  description= "Services found successfully"),
     * @OA\Response(
     *  response=404,
     *  description= "No services found")
     * )
     */
    public function showNombre(Request $request, $nombre)
    {

        $service = Servicio::where('nombre', 'LIKE', '%' . $nombre . '%')->get();

        if ($service->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No services found'
            ], 404);
        } else {
            return response()->json([
                'status' => 'success',
                'data' => $service
            ], 200);
        }
    }
}
