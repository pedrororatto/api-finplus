<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\GoalProgressController;
use App\Http\Controllers\NotificationController;


Route::get('/', function () {
    return response()->json(['message' => 'Finplus API - v2.0']);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', 'AuthController@logout');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/test', function () {
        return response()->json(['message' => 'Usuário logado']);
    });
    Route::group(['prefix' => 'user'], function () {
        // Transactions
        Route::apiResource('transactions', TransactionController::class);
        Route::get('transactions/total/by-expense', [TransactionController::class, 'transactionForEachExpenseCategory']);

        // Categories
        Route::apiResource('categories', CategoryController::class);

        //Goals
        Route::apiResource('goals', GoalController::class);

        // Goal Progress
        Route::get('goals/{goal}/progress', [GoalProgressController::class, 'index']);

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);

        //Dashboard
        Route::get('/dashboard', [DashboardController::class, 'dashboard']);

    });
});

/*Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', 'AuthController@logout');
    Route::get('/user', 'AuthController@user');
    Route::apiResource('transactions', 'TransactionController');
});*/

