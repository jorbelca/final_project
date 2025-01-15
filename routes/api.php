<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CostController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;

Route::apiResource('users', UserController::class);

// Show the budgets of one user
Route::get('users/{id}/budgets', [UserController::class, 'showUserBudgets']);

// Show the clients of one user
Route::get('users/{id}/clients', [UserController::class, 'showUserClients']);


Route::apiResource('clients', ClientController::class);

// Show the budgets of one client
Route::get('clients/{id}/budgets', [ClientController::class, 'showClientBudgets']);

Route::apiResource('budgets', BudgetController::class);

Route::apiResource('costs', CostController::class);

Route::apiResource('plans', PlanController::class);


Route::apiResource('subscriptions', SubscriptionController::class);


Route::fallback(function () {
    return response()->json([
        'message' => "There's nothing.",
        'status' => 404
    ], 404);
});
