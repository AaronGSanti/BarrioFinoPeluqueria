<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use App\Models\Cita;
use App\Models\User;
use App\Notifications\CitaRegistrada;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/citas",
     *     tags={"Appointments"},
     *     summary="Get a list of all appointments",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No appointments found"
     *     )
     * )
     */
    public function index()
    {
        $appoinments = Cita::all();

        if ($appoinments->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No appointments found'
            ], 404);
        } else {
            return response()->json([
                'status' => 'success',
                'data' => $appoinments
            ], 200);
        }
    }

    /**
     * @OA\Post(
     *  path= "/api/v1/citas/store",
     *  tags= {"Appointments"},
     *  summary= "Create a new appointment",
     * 
     * @OA\RequestBody(
     *  required= true,
     *  @OA\JsonContent(
     *  required= {"cliente_id","barbero_id","servicios_id","fecha_hora","hora_inicio","hora_fin","estado","precio_total"},
     *  @OA\Property(property="cliente_id", type="integer", example=1),
     *  @OA\Property(property="barbero_id", type="integer", example=2),
     *  @OA\Property(property="servicios_id", type="integer", example=3),
     *  @OA\Property(property="fecha_hora", type="string", format="date-time", example="2024-07-01 14:30:00"),
     *  @OA\Property(property="hora_inicio", type="string", format="time", example="14:30:00"),
     *  @OA\Property(property="hora_fin", type="string", format="time", example="15:00:00"),
     *  @OA\Property(property="estado", type="string", example="pendiente"),
     *  @OA\Property(property="precio_total", type="number", format="float",example=50.00))
     * ),
     * @OA\Response(
     *  response= 201,
     *  description= "Appointment created successfully"),
     * 
     * @OA\Response(
     *  response= 422,
     *  description= "Validation failed"
     *  )
     * )
     * 
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'cliente_id' => 'required|exists:users,id',
                'barbero_id' => 'nullable|exists:users,id',
                'servicio_id' => 'required|exists:servicios,id',
                'fecha_hora' => 'required|date_format:Y-m-d',
                'hora_inicio' => 'required|date_format:H:i:s',
                'hora_fin' => 'nullable|date_format:H:i:s|after:hora_inicio',
                'estado' => 'required|in:pendiente,confirmada,cancelada,completada',
                'precio_total' => 'required|numeric|min:0'
            ]);

            $exists = Cita::where('barbero_id', $request->barbero_id)
                ->where('fecha_hora', $request->fecha_hora)
                ->where('hora_inicio', $request->hora_inicio)
                ->whereIn('estado', ['pendiente', 'confirmada'])
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Ese horario ya esta reservado"
                ], 409);
            }

            $appointments = Cita::create($validatedData);
            //Traemos la cita con las relaciones para enviarlas en la notificacion
            $appointments->load('barbero');

            $cliente = User::find($validatedData['cliente_id']);
            //Enviamos notificacion al cliente
            if ($cliente) {
                $cliente->notify(new CitaRegistrada($appointments));
            }

            return response()->json([
                'status' => 'success',
                'data' => $appointments
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
     *  path="/api/v1/citas/put/{id}",
     *  tags={"Appointments"},
     *  summary="Update an existing appointment",
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      description="ID of the appointment to update",
     *      @OA\Schema(type="integer", example=1)
     *  ),
     *  @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(
     *          required= {"cliente_id","barbero_id","servicios_id","fecha_hora","hora_inicio","hora_fin","estado","precio_total"},
     *          @OA\Property(property="cliente_id", type="integer", example=1),
     *          @OA\Property(property="barbero_id", type="integer", example=2),
     *          @OA\Property(property="servicios_id", type="integer", example=3),
     *          @OA\Property(property="fecha_hora", type="string", format="date-time", example="2024-07-01 14:30:00"),
     *          @OA\Property(property="hora_inicio", type="string", format="time", example="14:30:00"),
     *          @OA\Property(property="hora_fin", type="string", format="time", example="15:00:00"),
     *          @OA\Property(property="estado", type="string", example="pendiente"),
     *          @OA\Property(property="precio_total", type="number", format="float", example=50.00)
     *      )
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Appointment updated successfully"),
     *  @OA\Response(
     *      response=404,
     *      description= "Appointment not found"),
     *  @OA\Response(
     *      response=422,
     *      description= "Validation failed"
     *  )
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'cliente_id' => 'sometimes|exists:users,id',
                'barbero_id' => 'sometimes|nullable|exists:users,id',
                'servicios_id' => 'sometimes|exists:servicios,id',
                'fecha_hora' => 'sometimes|date_format:Y-m-d',
                'hora_inicio' => 'sometimes|date_format:H:i:s',
                'hora_fin' => 'sometimes|date_format:H:i:s|after:hora_inicio',
                'estado' => 'sometimes|in:pendiente,confirmada,cancelada,completada',
                'precio_total' => 'sometimes|numeric|min:0'
            ]);

            $appointments = Cita::find($id);

            if (!$appointments) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Appointment not found'
                ], 404);
            } else {
                $appointments->update($validatedData);

                return response()->json([
                    'status' => 'success',
                    'data' => $appointments
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
     *  path="/api/v1/citas/delete/{id}",
     *  tags={"Appointments"},
     *  summary="Delete an appointment",
     * 
     *  @OA\Parameter(
     *    name="id",
     *    in="path",
     *    required=true,
     *    description="ID of the appointment to delete",
     *    @OA\Schema(type="integer", example=1)
     * ),
     * @OA\Response(
     *  response=200,
     *  description="Appointment deleted successfully"),
     * @OA\Response(
     *  response=404,
     *  description="Appointment not found")
     * )
     */
    public function delete($id)
    {
        $appoinments = Cita::find($id);

        if (!$appoinments) {
            return response()->json([
                'status' => 'error',
                'message' => 'Appointment not found'
            ], 404);
        } else {
            $appoinments->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Appointment deleted successfuylly'
            ], 200);
        }
    }

    /**
     * @OA\Get(
     *  path="/api/v1/citas/searchClienteCita",
     *  tags={"Appointments"},
     *  summary="Search appointments by client name",
     *  @OA\Parameter(
     *    name="q",
     *    in="query",
     *    required=false,
     *    description="Client name to search for",
     *    @OA\Schema(type="string", example="John")),
     * @OA\Response(
     *  response=200,
     *  description="Successful operation"),
     * @OA\Response(
     *  response=404,
     *  description="No appointments found")
     * )
     */
    public function showClienteCita(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $citas = Cita::query()
            ->with('cliente:id,name')
            ->when($q !== '', function ($query) use ($q) {
                //Dame solo las citas cuyo cliente tenga un nombre que coincida con el termino de busqueda
                $query->whereHas('cliente', function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%");
                });
            })
            ->get();

        if ($citas->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No appointments found'
            ], 404);
        } else {
            return response()->json([
                'status' => 'success',
                'data' => $citas
            ], 200);
        }
    }

    /**
     * @OA\Get(
     *  path="/api/v1/citas/searchBarberoCita",
     *  tags={"Appointments"},
     *  summary="Search appointments by barber name",
     *  @OA\Parameter(
     *   name="q",
     *   in="query",
     *   required=false,
     *   description="Barber name to search for",
     *   @OA\Schema(type="string", example="Mike")
     *  ),
     * @OA\Response(
     *  response=200,
     *  description="Successfull operation"),
     * @OA\Response(
     *  response=404,
     *  description="No appointments found")
     * )
     */
    public function showBarberoCita(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $citas = Cita::query()
            ->with('barbero:id,name')
            ->when($q !== '', function ($query) use ($q) {
                $query->whereHas('barbero', function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%");
                });
            })
            ->get();

        if ($citas->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No appointments found'
            ], 404);
        } else {
            return response()->json([
                'status' => 'success',
                'data' => $citas
            ], 200);
        }
    }

    /**
     * @OA\Get(
     *  path="/api/v1/citas/searchFechaCita",
     *  tags={"Appointments"},
     *  summary="Search appointments by date",
     *  @OA\Parameter(
     *   name="date",
     *   in="query",
     *   required=false,
     *   description="Date to search for (YYYY-MM-DD)",
     *   @OA\Schema(type="string", format="date", example="2024-07-01")),
     * @OA\Response(
     *  response=200,
     *  description = "Successful operation"),
     * @OA\Response(
     *  response=404,
     *  description= "No appointments found")
     * )
     */
    public function showFechaCita(Request $request)
    {
        $date = $request->query('date');

        $citas = Cita::query()
            ->with([
                'cliente:id,name',
                'barbero:id,name'
            ])
            ->when($date, function ($query) use ($date) {
                $query->whereDate('fecha_hora', $date);
            })
            ->get();

        if ($citas->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No appointments found'
            ], 404);
        } else {
            return response()->json([
                'status' => 'success',
                'data' => $citas
            ], 200);
        }
    }
}
