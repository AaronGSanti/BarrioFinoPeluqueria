<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *    path="/api/v1/register",
     *    tags={"Authentication"},
     *    summary="Register a new user",
     *   @OA\RequestBody(
     *      required=true,
     *     @OA\JsonContent(
     *        required={"name","email","password","password_confirmation"},
     *       @OA\Property(property="name", type="string", example="John Doe"),
     *       @OA\Property(property="email", type="string", format="email", example="doe@gmail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="password123"),
     *       @OA\Property(property="password_confirmation", type="string", format="password", example="password123"),
     *      )
     *  ),
     *  @OA\Response(
     *    response=201,
     *    description="User registered successfully"),
     * @OA\Response(
     *    response=409,
     *    description="Email already exists"),
     * @OA\Response(
     *    response=422,
     *    description="Validation failed")
     * )
     */
    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }


        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email already exists'
            ], 409);
        } else {
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password'])
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully',
                'user' => $user
            ], 201);
        }
    }

    /**
     * @OA\Post(
     *      path= "/api/v1/login",
     *      tags= {"Authentication"},
     *      summary= "Login a user and obtain an access token",
     * 
     * @OA\RequestBody(
     *      required= true,
     * @OA\JsonContent(
     *      required= {"email" , "password"},
     * 
     * @OA\Property(property= "email", type= "string", format= "email", example= "regusa2008@gmail.com"),
     * @OA\Property(property= "password", type= "string", format= "password", example= "password123"),
     *      )
     * ),
     * 
     * @OA\Response(
     * response= 200,
     * description= "User logged in successfully"),
     * 
     * @OA\Response(
     * response= 401,
     * description= "Invalid credentials"),
     * 
     * @OA\Response(
     * response= 422,
     * description= "Validation failed")
     * )
     */
    public function login(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        //Find user by email
        $user = User::where('email', $validatedData['email'])->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        } else {

            // Create token
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'message' => 'User logged in successfully',
                'access_token' => $token,
            ], 200);
        }
    }

    /**
     * @OA\Get(
     *  path ="/api/v1/profile" , 
     *  tags ={"Auth"},
     *  summary = "Get the authenticated users details",
     *  security = { {"bearerAuth": {}} },
     * 
     * @OA\Response(
     *  response = 200,
     *  description = "User details retrieved successfully"),
     * @OA\Response(
     *  response = 401,
     *  description = "Unauthorized")
     * )
     */
    public function user(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'user' => $request->user()
        ], 200);
    }

    /**
     * @OA\Post(
     *  path="/api/v1/logout",
     *  tags={"Auth"},
     *  summary="Logout the authenticated user",
     *  security = { {"bearerAuth": {}} },
     * 
     * @OA\Response(
     *  response= 200,
     *  description= "User logged out successfully"),
     * @OA\Response(
     *  response= 401,
     *  description= "Unauthorized")
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'User logged out successfully'
        ], 200);
    }
}
