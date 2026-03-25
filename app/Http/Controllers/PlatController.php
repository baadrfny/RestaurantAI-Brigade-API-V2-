<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlateController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $plates = Plate::where('is_available', true)
            ->with(['category', 'recommendations' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->get();

        $response = $plates->map(function ($plate) {
            $rec = $plate->recommendations->first();
            
            return [
                'id' => $plate->id,
                'name' => $plate->name,
                'description' => $plate->description,
                'price' => $plate->price,
                'category' => $plate->category->name ?? 'Uncategorized',
                'recommendation' => [
                    'status' => $rec->status ?? 'no_analysis', 
                    'score' => $rec->score ?? 0,
                    'label' => $rec->label ?? 'Not Analyzed',
                    'warning' => $rec->warning_message ?? null,
                ]
            ];
        });

        return response()->json($response, 200);
    }

    public function show($id)
    {
        $user = Auth::user();
        $plate = Plate::with(['category', 'ingredients', 'recommendations' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->findOrFail($id);

        return response()->json($plate);
    }
}
