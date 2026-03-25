<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminStatsController extends Controller
{
    /**
     * Display admin statistics.
     */
    public function index(Request $request)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $stats = [
            'total_users' => User::count(),
            'total_clients' => User::where('role', 'client')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'recent_registrations' => User::where('created_at', '>=', now()->subDays(7))->count(),
            'users_by_dietary_preferences' => $this->getUsersByDietaryPreferences(),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get users grouped by dietary preferences.
     */
    private function getUsersByDietaryPreferences()
    {
        $users = User::where('role', 'client')->get();
        $dietaryStats = [];
        
        foreach ($users as $user) {
            foreach ($user->dietary_tags as $tag) {
                $dietaryStats[$tag] = ($dietaryStats[$tag] ?? 0) + 1;
            }
        }
        
        return $dietaryStats;
    }
}
