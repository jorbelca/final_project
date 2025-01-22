<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\BudgetViewController;
use App\Http\Controllers\ClientViewController;
use App\Http\Controllers\CostViewController;
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


Route::resource('budgets', BudgetViewController::class);
Route::resource('costs', CostViewController::class);
Route::resource('clients', ClientViewController::class);

// Generate PDF
Route::get('/budget/{id}/generate', [BudgetController::class, 'generatePdf']);
