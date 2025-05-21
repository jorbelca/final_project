<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class StatisticsController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        // Get budget statistics
        $budgetsCount = $user->budgets()->count();
        $budgetsByState = $user->budgets()
            ->selectRaw('state, COUNT(*) as count')
            ->groupBy('state')
            ->pluck('count', 'state')
            ->toArray();

        // Get client count
        $clientsCount = $user->clients()->count();

        // Get clients with their budget statistics
        $clientBudgetStats = $user->clients()
            ->with(['budgets:id,client_id,state'])
            ->get()
            ->map(function ($client) {
                $budgetsByState = $client->budgets->groupBy('state')
                    ->map(function ($group) {
                        return count($group);
                    })->toArray();

                return [
                    'client_name' => $client->name ?? 'Unknown',
                    'total_budgets' => $client->budgets->count(),
                    'budgets_by_state' => $budgetsByState
                ];
            });

        // Combine statistics
        $budgetsStats = [
            'total' => $budgetsCount,
            'by_state' => $budgetsByState
        ];

        $clientByBudgetState = $clientBudgetStats;


        return Inertia::render('Stats/Stats', [
            'budgetsStats' => $budgetsStats,
            'clientsCount' => $clientsCount,
            'clientByBudgetState' => $clientByBudgetState
        ]);
    }
}
