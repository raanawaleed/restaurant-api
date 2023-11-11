<?php

use Illuminate\Http\Request;

use App\Http\Controllers\DishController;
use App\Http\Controllers\AuthUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('login', [AuthUserController::class, 'login']);
Route::post('/register', [AuthUserController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::get('me', [AuthUserController::class, 'me']);
    Route::post('logout', [AuthUserController::class, 'logout']);
    Route::post('refresh', [AuthUserController::class, 'refresh']);
});

Route::middleware(['auth:api', 'throttle:60,1'])->group(function () {
    Route::prefix('dishes')->group(function () {
        Route::get('/', [DishController::class, 'index']);        // List all dishes
        Route::get('/{id}', [DishController::class, 'show']);     // Get details of a specific dish
        Route::post('/', [DishController::class, 'store']);       // Create a new dish
        Route::put('/{id}', [DishController::class, 'update']);   // Update an existing dish
        Route::delete('/{id}', [DishController::class, 'destroy']); // Delete a dish

        // Rate a dish
        Route::post('/{id}/rate', [DishController::class, 'rate']);
    });
});
