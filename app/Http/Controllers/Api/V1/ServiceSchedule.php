<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\HorariosBarbero;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ServiceSchedule extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/v1/HorariosBarbero",
     *   tags={"Barber Schedule"},
     *   summary="List barber schedules with filters",
     *   description="Returns barber schedules filtered by barber, day of week, status and time range.",
     *
     *   @OA\Parameter(
     *     name="barbero_id",
     *     in="query",
     *     required=false,
     *     description="Filter by barber ID",
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *
     *   @OA\Parameter(
     *     name="barbero",
     *     in="query",
     *     required=false,
     *     description="Filter by barber name (partial match)",
     *     @OA\Schema(type="string", example="Juan")
     *   ),
     *
     *   @OA\Parameter(
     *     name="dia_semana",
     *     in="query",
     *     required=false,
     *     description="Day of week (1=Lunes, 7=Domingo)",
     *     @OA\Schema(type="integer", minimum=1, maximum=7, example=1)
     *   ),
     *
     *   @OA\Parameter(
     *     name="estado",
     *     in="query",
     *     required=false,
     *     description="Schedule status",
     *     @OA\Schema(type="string", enum={"disponible","no_disponible"}, example="disponible")
     *   ),
     *
     *   @OA\Parameter(
     *     name="desde",
     *     in="query",
     *     required=false,
     *     description="Filter schedules that end after this time",
     *     @OA\Schema(type="string", format="time", example="09:00")
     *   ),
     *
     *   @OA\Parameter(
     *     name="hasta",
     *     in="query",
     *     required=false,
     *     description="Filter schedules that start before this time",
     *     @OA\Schema(type="string", format="time", example="18:00")
     *   ),
     *
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     required=false,
     *     description="Pagination page",
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Successful response"
     *   ),
     *
     *   @OA\Response(
     *     response=422,
     *     description="Invalid parameters"
     *   )
     * )
     */
    public function index(Request $request)
    {
        /**Filtra por cada campo que tenemos para realizar el calendario y luego mostrarlo todo separado , asi no muestra todos. */
        $q = HorariosBarbero::query()
            ->with([
                'barbero:id,name'
            ]);

        if ($request->filled('barbero_id')) {
            $q->where('barbero_id', $request->barbero_id);
        }

        if ($request->filled('barbero')) {
            $name = $request->barbero;
            $q->whereHas('barbero', fn($u) => $u->where('name', 'like', "%{$name}%"));
        }

        if ($request->filled('dia_semana')) {
            $q->where('dia_semana', (int)$request->dia_semana);
        }

        if ($request->filled('estado')) {
            $q->where('estado', $request->estado);
        }

        if ($request->filled('desde')) {
            $q->where('hora_fin', '>', $request->desde);
        }

        if ($request->filled('hasta')) {
            $q->where('hora_inicio', '<', $request->hasta);
        }

        return $q->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->paginate(20);
    }

    /**
     * @OA\Post(
     *   path="/api/v1/HorariosBarbero/store",
     *   tags={"Barber Schedule"},
     *   summary="Create a new barber schedule",
     *
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"barbero_id","dia_semana","hora_inicio","hora_fin","estado"},
     *       @OA\Property(property="barbero_id", type="integer", example=1),
     *       @OA\Property(property="dia_semana", type="integer", example=1),
     *       @OA\Property(property="hora_inicio", type="string", format="time", example="14:00:00"),
     *       @OA\Property(property="hora_fin", type="string", format="time", example="14:30:00"),
     *       @OA\Property(
     *         property="estado",
     *         type="string",
     *         enum={"disponible","no_disponible"},
     *         example="disponible"
     *       )
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=201,
     *     description="Barber schedule created successfully"
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Validation failed"
     *   )
     * )
     */

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'barbero_id' => 'required|exists:users,id',
                'dia_semana' => 'required|integer|min:1|max:7',
                'hora_inicio' => 'required|date_format:H:i:s',
                'hora_fin' => 'required|date_format:H:i:s',
                'estado' => 'required|in:disponible,no_disponible',
            ]);

            $barberSchedule = HorariosBarbero::create($validatedData)
                ->load([
                    'barbero:id,name'
                ]);

            return response()->json([
                'status' => 'success',
                'data' => $barberSchedule
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
     *   path="/api/v1/HorariosBarbero/put/{id}",
     *   tags={"Barber Schedule"},
     *   summary="Update an existing barber schedule",
     *
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="ID of the barber schedule to update",
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"barbero_id","dia_semana","hora_inicio","hora_fin","estado"},
     *       @OA\Property(property="barbero_id", type="integer", example=1),
     *       @OA\Property(property="dia_semana", type="integer", example=1, description="1=Lunes ... 7=Domingo"),
     *       @OA\Property(property="hora_inicio", type="string", example="14:00:00"),
     *       @OA\Property(property="hora_fin", type="string", example="14:30:00"),
     *       @OA\Property(property="estado", type="string", enum={"disponible","no_disponible"}, example="no_disponible")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Barber schedule updated successfully"
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Barber schedule not found"
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Validation failed"
     *   )
     * )
     */

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'barbero_id' => 'sometimes|exists:users,id',
                'dia_semana' => 'required|integer|min:1|max:7',
                'hora_inicio' => 'sometimes|date_format:H:i:s',
                'hora_fin' => 'sometimes|date_format:H:i:s',
                'estado' => 'sometimes|in:disponible,no_disponible'
            ]);

            $barberSchedule = HorariosBarbero::find($id);

            if (!$barberSchedule) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Barber schedule not found'
                ], 404);
            } else {
                $barberSchedule->update($validatedData);

                return response()->json([
                    'status' => 'success',
                    'data' => $barberSchedule
                ], 200);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'error' => $e->errors()
            ], 422);
        }
    }

    /**
     * @OA\Delete(
     *  path="/api/v1/HorariosBarbero/delete/{id}",
     *  tags={"Barber Schedule"},
     *  summary="Delete an barber schedule",
     * 
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      description="ID of the appointment to delete",
     *      @OA\Schema(type="integer", example=1)
     *  ),
     * @OA\Response(
     *      response=200,
     *      description="Barber schedule deleted successfully"
     *  ),
     * @OA\Response(
     *      response=404,
     *      description="Barber schedule not found"
     *  )
     * )
     */
    public function delete($id)
    {
        $barberSchedule = HorariosBarbero::find($id);

        if (!$barberSchedule) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barber schedule not found'
            ], 404);
        } else {
            $barberSchedule->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Barber schedule deleted successfully'
            ], 200);
        }
    }

    public function semana(Request $request)
    {
        $request->validate([
            'barbero_id' => 'required|exists:users,id'
        ]);

        $horarios = HorariosBarbero::query()
            ->with([
                'barbero:id,name'
            ])
            ->where('barbero_id', $request->barbero_id)
            ->where('estado', 'disponible')
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->get()
            ->groupBy('dia_semana');

        return response()->json([
            'lunes'     => $horarios[1] ?? [],
            'martes'    => $horarios[2] ?? [],
            'miercoles' => $horarios[3] ?? [],
            'jueves'    => $horarios[4] ?? [],
            'viernes'   => $horarios[5] ?? [],
            'sabado'    => $horarios[6] ?? [],
            'domingo'   => $horarios[7] ?? [],
        ]);
    }
}
