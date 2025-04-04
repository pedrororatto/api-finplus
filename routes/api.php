<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return response()->json(['message' => 'Finplus API - v2.0']);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/dashboard', function () {
        return response()->json(['message' => 'Welcome to the dashboard']);
    });
});

/*Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', 'AuthController@logout');
    Route::get('/user', 'AuthController@user');
    Route::apiResource('transactions', 'TransactionController');
});*/

