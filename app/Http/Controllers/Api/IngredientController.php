<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    /**
     * Store a newly created ingredient (admin only).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:ingredients',
            'tags' => 'sometimes|array',
            'tags.*' => 'string|in:contains_meat,contains_sugar,contains_cholesterol,contains_gluten,contains_lactose'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $ingredient = Ingredient::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Ingredient created successfully',
            'data' => $ingredient
        ], 201);
    }

    /**
     * Update the specified ingredient (admin only).
     */
    public function update(Request $request, $id)
    {
        $ingredient = Ingredient::find($id);

        if (!$ingredient) {
            return response()->json([
                'success' => false,
                'message' => 'Ingredient not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255|unique:ingredients,name,' . $id,
            'tags' => 'sometimes|array',
            'tags.*' => 'string|in:contains_meat,contains_sugar,contains_cholesterol,contains_gluten,contains_lactose'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $ingredient->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Ingredient updated successfully',
            'data' => $ingredient
        ]);
    }

    /**
     * Remove the specified ingredient (admin only).
     */
    public function destroy($id)
    {
        $ingredient = Ingredient::find($id);

        if (!$ingredient) {
            return response()->json([
                'success' => false,
                'message' => 'Ingredient not found'
            ], 404);
        }

        // Check if ingredient is associated with plates
        if ($ingredient->plates()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete ingredient associated with plates'
            ], 400);
        }

        $ingredient->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ingredient deleted successfully'
        ]);
    }
}
