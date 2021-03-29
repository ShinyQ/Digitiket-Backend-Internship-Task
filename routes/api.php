<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DestinationController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function(){
    Route::apiResource("destination", DestinationController::class);

    Route::prefix('user')->group(function(){
        Route::post("/register", [UserController::class, 'register']);
        Route::post("/login", [UserController::class, 'login']);
        Route::get("/logout", [UserController::class, 'logout']);
        Route::middleware('user')->get('/', [UserController::class, 'index']);
    });
});


