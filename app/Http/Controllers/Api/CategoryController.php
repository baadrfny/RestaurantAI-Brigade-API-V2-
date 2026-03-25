<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request)
    {
        $categories = Category::withCount('plats')->get();
        
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Display the specified category.
     */
    public function show($id)
    {
        $category = Category::with('plats')->find($id);
        
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }
}
