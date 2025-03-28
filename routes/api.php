<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;


Route::get('/', function () {
    return response()->json(['message' => 'Finplus API - v2.0']);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/test', function () {
        return response()->json(['message' => 'Teste']);
    });
    Route::group(['prefix' => 'user'], function () {
        Route::group(['prefix' => 'transactions'], function () {
            Route::get('/', [TransactionController::class, 'index']);
            Route::post('/', [TransactionController::class, 'store']);
            Route::get('/{transaction}', [TransactionController::class, 'show']);
            Route::put('/{transaction}', [TransactionController::class, 'update']);
            Route::delete('/{transaction}', [TransactionController::class, 'destroy']);
        });
    });
});

/*Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', 'AuthController@logout');
    Route::get('/user', 'AuthController@user');
    Route::apiResource('transactions', 'TransactionController');
});*/

