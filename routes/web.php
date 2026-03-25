<?php

// resources/routes/web.php

Route::get('/', function () { return view('welcome'); });

// Add a simple login route to satisfy Laravel's default auth configuration
Route::get('/login', function () {
    return response()->json([
        'message' => 'API-only application. Use /api/login for authentication.'
    ], 404);
})->name('login');