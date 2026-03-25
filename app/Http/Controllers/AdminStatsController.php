<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plate;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Recommendation;
use Illuminate\Http\Request;

class AdminStatsController extends Controller
{
    public function index()
    {
        $totalPlates = Plate::count();
        $totalCategories = Category::count();
        $totalIngredients = Ingredient::count();
        $totalRecommendations = Recommendation::count();

        $mostRecommended = Plate::withAvg('recommendations', 'score')
            ->orderByDesc('recommendations_avg_score')
            ->first(['id', 'name']);

        $leastRecommended = Plate::withAvg('recommendations', 'score')
            ->whereHas('recommendations') 
            ->orderBy('recommendations_avg_score')
            ->first(['id', 'name']);

        $topCategory = Category::withCount('plates')
            ->orderByDesc('plates_count')
            ->first(['id', 'name']);

        return response()->json([
            'overview' => [
                'plates' => $totalPlates,
                'categories' => $totalCategories,
                'ingredients' => $totalIngredients,
                'recommendations' => $totalRecommendations,
            ],
            'highlights' => [
                'most_recommended' => $mostRecommended,
                'least_recommended' => $leastRecommended,
                'top_category' => $topCategory,
            ]
        ], 200);
    }
}