<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    /**
     * Display a listing of ingredients.
     */
    public function index(Request $request)
    {
        $ingredients = Ingredient::with('plates')->get();
        
        return response()->json([
            'success' => true,
            'data' => $ingredients
        ]);
    }

    /**
     * Display the specified ingredient.
     */
    public function show($id)
    {
        $ingredient = Ingredient::with('plates')->find($id);
        
        if (!$ingredient) {
            return response()->json([
                'success' => false,
                'message' => 'Ingredient not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $ingredient
        ]);
    }
}
