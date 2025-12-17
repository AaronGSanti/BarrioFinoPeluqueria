<?php

namespace App\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 * title="Barrio Fino Peluqueria API",
 * version="1.0.0",
 * description="API documentation for Barrio Fino Peluqueria application")
 * 
 * @OA\Server(
 * url= "http://localhost:8000",
 * description="Local Development Server")
 * 
 * @OA\SecurityScheme(
 * securityScheme="bearerAuth",
 * type="http",
 * scheme="bearer")
 */
class OpenApi {}
