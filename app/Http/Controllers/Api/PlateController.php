<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plat;
use Illuminate\Http\Request;

class PlateController extends Controller
{
    /**
     * Display a listing of plates.
     */
    public function index(Request $request)
    {
        $plates = Plat::all();
        
        return response()->json([
            'success' => true,
            'data' => $plates
        ]);
    }

    /**
     * Display the specified plate.
     */
    public function show($id)
    {
        $plate = Plat::find($id);
        
        if (!$plate) {
            return response()->json([
                'success' => false,
                'message' => 'Plate not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $plate
        ]);
    }
}
