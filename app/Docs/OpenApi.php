<?php

/**
 * @OA\Info(
 *     title="Brigade API",
 *     version="1.0.0",
 *     description="API documentation for Brigade"
 * )
 */

/**
 * @OA\Server(
 *     url="http://127.0.0.1:8000",
 *     description="Local API Server"
 * )
 */

/**
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="apiKey",
 *     in="header",
 *     name="Authorization",
 *     description="Enter token in format (Bearer <token>)"
 * )
 */

/**
 * @OA\Components(
 *     @OA\Schema(
 *         schema="User",
 *         type="object",
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="name", type="string"),
 *         @OA\Property(property="email", type="string"),
 *         @OA\Property(property="role", type="string"),
 *         @OA\Property(property="created_at", type="string", format="date-time"),
 *         @OA\Property(property="updated_at", type="string", format="date-time")
 *     )
 * )
 */