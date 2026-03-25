<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plate;
use App\Models\Recommendation;
use App\Jobs\AnalyzePlateJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class RecommendationController extends Controller
{
    public function analyzePlate(Request $request)
    {

        $plate = Plate::findOrFail($plate_id);
        $user = Auth::user();

        $recommmendation = Recommendation::updateOrCreate([
            'plate_id' => $plate->id,
            'user_id' => $user->id,
        ], [
            'status' => 'pending',
            'score' => 0,
            'label' => null,
            'warning_message' => null,
        ]);
        

        // Dispatch job to analyze plate
        AnalyzePlateJob::dispatch($plate, $user, $recommendation);

        return response()->json([
            'message' => 'Plate analysis started',
            'status' => 'processing'
        ], 202);
    }

    public function show($plate_id){
        $plate = Plate::findOrFail($plate_id);
        $user = Auth::user();
        
        $recommendation = Recommendation::where('plate_id', $plate->id)
            ->where('user_id', $user->id)
            ->first();
        
        return response()->json([
        'plate_id' => $recommendation->plate_id,
        'status'   => $recommendation->status,
        'score'    => $recommendation->score,
        'label'    => $recommendation->label,
        'warning'  => $recommendation->warning_message,
        'message'  => $recommendation->status === 'ready' ? 'Analysis complete' : 'Analysis is still in progress'
    ], 200);
    }
}
