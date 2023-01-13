<?php

use App\Http\Controllers\api\AuthController;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/login',[AuthController::class, 'login']);
Route::post('/auth/register',[AuthController::class, 'register']);

Route::group(['Middleware' => ['auth:sanctum']], function(){
    Route::get('auth/profile', [AuthController::class, 'profile']);
    Route::put('auth/edit-profile', [AuthController::class, 'edit']);
    Route::post('auth/change-password', [AuthController::class, 'updatePassword']);
    Route::delete('auth/logout', [AuthController::class, 'logout']);
});
