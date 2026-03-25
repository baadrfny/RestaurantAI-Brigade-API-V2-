<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
   /**
 * @OA\Get(
 *     path="/api/test",
 *     summary="Test endpoint",
 *     tags={"Test"},
 *     @OA\Response(
 *         response=200,
 *         description="Success",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Hello World")
 *         )
 *     )
 * )
 */
    public function test()
    {
        return response()->json(['message' => 'Hello World']);
    }
}
