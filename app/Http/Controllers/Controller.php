<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 * title="RestaurantAI Brigade API",
 * version="1.0.0",
 * description="Documentation de l'API pour le système de recommandation nutritionnelle"
 * )
 *
 * @OA\Server(
 * url=L5_SWAGGER_CONST_HOST,
 * description="Serveur Local"
 * )
 *
 * @OA\SecurityScheme(
 * securityScheme="sanctum",
 * type="apiKey",
 * in="header",
 * name="Authorization",
 * description="Entrez le token sous la forme 'Bearer {token}'"
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
