<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recommendation;
use App\Models\Plat;
use App\Jobs\AnalyzePlateCompatibility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    /**
     * Display a listing of recommendations for the authenticated user.
     */
    public function index(Request $request)
    {
        $recommendations = Recommendation::where('user_id', Auth::id())
            ->with('plate')
            ->orderBy('score', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $recommendations
        ]);
    }

    /**
     * Display a specific recommendation.
     */
    public function show($plate_id, Request $request)
    {
        $recommendation = Recommendation::where('user_id', Auth::id())
            ->where('plate_id', $plate_id)
            ->with('plate')
            ->first();

        if (!$recommendation) {
            return response()->json([
                'success' => false,
                'message' => 'Recommendation not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $recommendation
        ]);
    }

    /**
     * Analyze a plate for the authenticated user using Grok AI.
     */
    public function analyzePlate($plate_id, Request $request)
    {
        $plate = Plat::find($plate_id);
        
        if (!$plate) {
            return response()->json([
                'success' => false,
                'message' => 'Plate not found'
            ], 404);
        }

        $user = Auth::user();
        $userRestrictions = $user->dietary_tags ?? [];

        // Create initial recommendation as "processing"
        $recommendation = Recommendation::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'plate_id' => $plate_id,
            ],
            [
                'status' => 'processing',
                'score' => 0,
                'label' => 'Analyzing...',
                'warning_message' => null,
            ]
        );

        // Dispatch AI analysis job
        AnalyzePlateCompatibility::dispatch(
            Auth::id(),
            $plate_id,
            [
                'name' => $plate->name,
                'description' => $plate->description,
                'price' => $plate->price
            ],
            $userRestrictions
        );

        return response()->json([
            'success' => true,
            'message' => 'AI analysis started',
            'data' => $recommendation
        ]);
    }
}
