<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\BudgetViewController;
use App\Http\Controllers\ClientViewController;
use App\Http\Controllers\CostViewController;
use App\Http\Controllers\PromptViewController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StatisticsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register')
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('budgets.index');
    })->name('dashboard');

    Route::resource('budgets', BudgetViewController::class);
    Route::resource('costs', CostViewController::class)->except('show');
    Route::resource('clients', ClientViewController::class)->except('show');
    Route::resource('support', SupportController::class);
    Route::resource('subscription', SubscriptionController::class)->except('show');
    Route::resource('prompt', PromptViewController::class)->except('show');


    //Update Client with POST Request
    Route::post('/clients/update/{id}', [ClientViewController::class, 'update']);


    //Vinculate Client with POST Request
    Route::post('/clients/vinculate', [ClientViewController::class, 'vinculate']);

    // Generate PDF
    Route::get('/budget/{id}/generate', [BudgetController::class, 'generatePdf']);
    // Clone budget
    Route::get('/budget/{id}/clone', [BudgetViewController::class, 'clone']);

    //Admin
    Route::get('/admin', [UserController::class, 'admin'])->name("admin");

    //Parse costs
    Route::get('/costs/parse', [CostViewController::class, 'parse'])->name('costs.parse');

    //InsertMultiple costs
    Route::post('/costs/store_multiple', [CostViewController::class, 'storeMultiple'])->name('costs.storeMultiple');

    //Cambiar estado budget
    Route::post('/users/{id}/changestate', [UserController::class, 'changeState']);

    //Estadisticas
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');

    //FALLBACK
    Route::fallback(function () {
        return Inertia::render('404');
    });
});

// Politicas de la web
Route::get('/terms', function () {
    return Inertia::render('TermsOfService');
});
