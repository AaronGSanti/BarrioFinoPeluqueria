<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use App\Models\BarberoServicio;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ServiceBarberController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/v1/BarberoServicio",
     *  tags={"Service Barber"},
     *  summary="Get a list of barber-service relations",
     *  description="Returns barber-service relations. Supports by barber name and    service",
     * 
     *  @OA\Parameter(
     *      name="barbero",
     *      in="query",
     *      required=false,
     *      description="Filter by barber name",
     *      @OA\Schema(type="string", example="Juan")
     *  ),
     *  @OA\Parameter(
     *      name="servicio",
     *      in="query",
     *      required=false,
     *      description="Filter by service name",
     *      @OA\Schema(type="string", example="corte")
     *  ),
     *  @OA\Parameter(
     *      name="page",
     *      in="query",
     *      required=false,
     *      description="Pagination page number",
     *      @OA\Schema(type="integer", example=1)
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Successful response"
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="Validation error"
     *  )
     * )
     */
    public function index(Request $request)
    {
        $query = BarberoServicio::query()
            ->with([
                'barbero:id,name',
                'servicio:id,nombre'
            ]);

        if ($request->filled('barbero')) {
            //Sacamos valor del request y lo guardamos como una variable.
            $barbero = $request->barbero;
            $query->whereHas('user', function ($q) use ($barbero) {
                $q->where('name', 'like', "%{$barbero}%");
            });
        }

        if ($request->filled('servicio')) {
            $servicio = $request->servicio;
            $query->whereHas('servicio', function ($q) use ($servicio) {
                $q->where('nombre', 'like', "%{$servicio}%");
            });
        }

        return $query->paginate(20);
    }

    /**
     * @OA\Post(
     *  path="/api/v1/BarberoServicio/store",
     *  tags={"Service Barber"},
     *  summary="Create a new barber - service",
     *  
     *  @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(
     *          required={"barbero_id", "servicio_id"},
     *      @OA\Property(property="barbero_id", type="integer" , example=1),
     *      @OA\Property(property="servicio_id", type="integer", example=2)
     *      )
     *  ),
     *  @OA\Response(
     *   response=201,
     *   description="Barber service created successfully"
     *  ),
     *  @OA\Response(
     *   response=422,
     *   description="Validation failed"
     *  )
     * )
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'barbero_id' => [
                    'required',
                    Rule::exists('users', 'id')->where(fn($q) => $q->where('role', 'barbero')),
                ],
                'servicio_id' => 'required|exists:servicios,id'
            ]);

            $service_barber = BarberoServicio::create($validatedData)
                ->load([
                    'barbero:id,name',
                    'servicio:id,nombre'
                ]);

            return response()->json([
                'status' => 'success',
                'data' => $service_barber
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
     * @OA\Delete(
     *  path="/api/v1/BarberoServicio/delete/{id}",
     *  tags={"Service Barber"},
     *  summary="Delete an service barber",
     * 
     * @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      description="ID of the service barber to delete",
     *      @OA\Schema(type="integer" , example=1)
     *  ),
     *  @OA\Response(
     *   response=200,
     *   description="Service barber deleted successfully"
     *  ),
     *  @OA\Response(
     *   response=404,
     *   description="Service barber not found"
     *  )
     * )
     */
    public function delete($id)
    {
        $service_barber = BarberoServicio::find($id);

        if ($service_barber->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barber service not found'
            ], 404);
        } else {
            $service_barber->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Barber service deleted successfully'
            ], 200);
        }
    }
}
