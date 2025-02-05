<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\BudgetViewController;
use App\Http\Controllers\ClientViewController;
use App\Http\Controllers\CostViewController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [BudgetViewController::class, 'index'])->name('dashboard');
});


Route::resource('budgets', BudgetViewController::class)->middleware("auth");
Route::resource('costs', CostViewController::class)->middleware("auth");
Route::resource('clients', ClientViewController::class)->middleware("auth");

// Generate PDF
Route::get('/budget/{id}/generate', [BudgetController::class, 'generatePdf']);
//Admin
Route::get('/admin', [UserController::class, 'admin'])->name("admin");


Route::post('/users/{id}/changestate', [UserController::class, 'changeState']);
