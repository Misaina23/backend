<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProducerController;
use App\Http\Controllers\Api\InspectionController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Login API endpoint - retourne le rôle
Route::post('/login', function () {
    $credentials = request()->only('email', 'password');

    // Find user by email
    $user = User::where('email', $credentials['email'])->first();

    if ($user && Hash::check($credentials['password'], $user->password)) {
        // Create token (simple token for now)
        $token = base64_encode($user->id . ':' . time());

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
            ],
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Identifiants invalides',
    ], 401);
});

// Web Admin Login - only for admin role
Route::post('/web/login', function () {
    $credentials = request()->only('email', 'password');

    $user = User::where('email', $credentials['email'])->first();

    if ($user && Hash::check($credentials['password'], $user->password)) {
        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Accès réservé aux administrateurs',
            ], 403);
        }

        $token = base64_encode($user->id . ':' . time() . ':web');

        return response()->json([
            'success' => true,
            'message' => 'Connexion administrateur réussie',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
            ],
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Identifiants invalides',
    ], 401);
});

// Producer routes
Route::prefix('producers')->group(function () {
    Route::get('/', [ProducerController::class, 'index']);
    Route::post('/', [ProducerController::class, 'store']);
    Route::post('batch-sync', [ProducerController::class, 'batchSync']);
    Route::post('validate-gps', [ProducerController::class, 'validateGps']);
    Route::get('statistics', [ProducerController::class, 'statistics']);
    Route::get('{id}', [ProducerController::class, 'show']);
    Route::put('{id}', [ProducerController::class, 'update']);
    Route::delete('{id}', [ProducerController::class, 'destroy']);
});

// Inspection routes
Route::prefix('inspections')->group(function () {
    Route::get('/', [InspectionController::class, 'index']);
    Route::post('/', [InspectionController::class, 'store']);
    Route::get('statistics', [InspectionController::class, 'statistics']);
    Route::get('producer/{codeProducteur}', [InspectionController::class, 'byProducer']);
    Route::get('{id}', [InspectionController::class, 'show']);
    Route::put('{id}', [InspectionController::class, 'update']);
    Route::delete('{id}', [InspectionController::class, 'destroy']);
});

// Dashboard statistics
Route::get('dashboard', function () {
    $producerStats = [
        'total' => \App\Models\Producer::count(),
        'total_superficie' => \App\Models\Producer::sum('superficie'),
        'total_chiffre_affaires' => \App\Models\Producer::sum('chiffre_affaires'),
    ];

    $inspectionStats = [
        'total' => \App\Models\Inspection::count(),
        'this_month' => \App\Models\Inspection::whereMonth('date_inspection', now()->month)
            ->whereYear('date_inspection', now()->year)
            ->count(),
    ];

    return response()->json([
        'success' => true,
        'data' => [
            'producers' => $producerStats,
            'inspections' => $inspectionStats,
        ],
    ]);
});
