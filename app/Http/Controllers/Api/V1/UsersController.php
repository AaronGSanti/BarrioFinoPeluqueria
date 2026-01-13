<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/v1/usuarios",
     *  tags={"Users"},
     *  summary= "Get all users",
     *  description="Retrieve a list of all users",
     *  @OA\Response(
     *   response=200,
     *   description="Successfull operation"),
     *  @OA\Response(
     *   response=404,
     *   description="No users found")
     * )
     */
    public function index()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No users found'
            ], 404);
        } else {
            return response()->json([
                'status' => 'success',
                'data' => $users
            ], 200);
        }
    }

    /**
     * @OA\Post(
     *  path="/api/v1/usuarios/store", 
     *  tags= {"Users"},
     *  summary = "Create a new user",
     *  description = "Create a new user with the provided information",
     *  @OA\RequestBody(
     *   required = true,
     *  @OA\JsonContent(
     *   required = {"name","email","password","role" , "phone_number" , "photo_url"},
     *  @OA\Property(property="name", type="string", example="John Doe"),
     *  @OA\Property(property="email", type="string", format="email", example="doe@gmail.com"),
     *  @OA\Property(property="password", type="string", format="password", example="password123"),
     *  @OA\Property(property="role", type="string", example="cliente"),
     *  @OA\Property(property="phone_number", type="string", example="+1234567890"),
     *  @OA\Property(property="photo_url", type="string", format="url", example="http://example.com/photo.jpg")
     *   )
     * ),
     * @OA\Response(
     *  response= 201,
     *  description= "User created successfully"),
     * @OA\Response(
     *  response= 422,
     *  description= "Validation failed")
     * )
     * 
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone_number' => 'required|string|max:30',
                'photo_url' => 'nullable|url',
                'role' => [
                    'nullable',
                    Rule::in(['cliente', 'barbero', 'admin'])
                ]
            ]);

            $validatedData['role'] = $validatedData['role'] ?? 'cliente';
            $validatedData['password'] = Hash::make($validatedData['password']);

            $user = User::create($validatedData);

            return response()->json([
                'status' => 'success',
                'data' => $user
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
     * @OA\Get(
     *  path= "/api/v1/usuarios/search/{nombre}",
     *  tags= { "Users" },
     *  summary= "Search users by nombre",
     * 
     * @OA\Parameter(
     *  name= "nombre",
     *  in= "path",
     *  required= true,
     *  description= "Nombre of the users to search for",
     *  @OA\Schema(type= "string" , example= "John")
     *  ),
     * @OA\Response(
     *  response=200,
     *  description= "Users found successfully"),
     * @OA\Response(
     *  response=404,
     *  description= "No users found")
     * )
     */
    public function showNombre($nombre)
    {
        $users = User::where('name', 'LIKE', '%' . $nombre . '%')->get();

        if ($users->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No users found'
            ], 404);
        } else {
            return response()->json([
                'status' => 'success',
                'data' => $users
            ], 200);
        }
    }

    /**
     * @OA\Get(
     *   path="/api/v1/usuarios/barberos",
     *   tags={"Users"},
     *   summary="Get all barbers",
     *
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(
     *         property="data",
     *         type="array",
     *         @OA\Items(
     *           type="object",
     *           @OA\Property(property="id", type="integer", example=1),
     *           @OA\Property(property="name", type="string", example="David"),
     *           @OA\Property(property="role", type="string", example="barbero")
     *         )
     *       )
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=404,
     *     description="No barbers found",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="error"),
     *       @OA\Property(property="message", type="string", example="No barbers found")
     *     )
     *   )
     * )
     */
    public function showBarbero()
    {
        $barberos = User::where('role', 'barbero')->get();

        if ($barberos->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No barbers found'
            ], 404);
        } else {
            return response()->json([
                'status' => 'success',
                'data' => $barberos
            ], 200);
        }
    }
}
