<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class StatisticsController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        // Get budget statistics

        $rawBudgets = $user->budgets;
        // Get client count
        $clientsCount = $user->clients()->count();

        $budgetsByState = StatisticsController::sliceInfo($rawBudgets->groupBy('state'));



        // Get clients with their budget statistics
        $clientBudgetStats = $user->clients()
            ->with(['budgets:id,client_id,state,content'])
            ->get()
            ->map(function ($client) {
                $budgetsByState = StatisticsController::sliceInfo($client->budgets->groupBy('state'));

                return [
                    'client_name' => $client->name ?? 'Unknown',
                    'budgets_by_state' => $budgetsByState,
                ];
            });



        // Calculate total budget amount for this client
        $totalAmount = $user->budgets->reduce(function ($total, $budget) {
            $content = is_string($budget->content) ? json_decode($budget->content, true) : $budget->content;

            if (empty($content)) {
                return $total;
            }

            // Handle array of items in content
            if (isset($content[0])) {
                $itemTotal = collect($content)->sum(function ($item) {
                    return ($item['quantity'] ?? 0) * ($item['cost'] ?? 0);
                });
            } else {
                // Handle single item structure
                $itemTotal = ($content['quantity'] ?? 0) * ($content['cost'] ?? 0);
            }

            return $total + $itemTotal;
        }, 0);

        // Combine statistics
        $budgetsStats = [
            'total_amount' => $totalAmount,
            'by_state' => $budgetsByState,
        ];

        $clientByBudgetState = $clientBudgetStats;

        $user->profesional = $user->subscription->plan_id === 3 ? true : false;

        return Inertia::render('Stats/Stats', [
            'budgetsStats' => $budgetsStats,
            'clientsCount' => $clientsCount,
            'clientByBudgetState' => $clientByBudgetState
        ]);
    }



    static  public function sliceInfo($budgets)
    {
        return $budgets->map(function ($budgetsInState) {
            // Calculate total amount for all budgets in this state
            $totalAmount = $budgetsInState->reduce(function ($total, $budget) {
                $content = is_string($budget->content) ? json_decode($budget->content, true) : $budget->content;

                if (empty($content)) {
                    return $total;
                }

                // Handle array of items in content
                if (isset($content[0])) {
                    $itemTotal = collect($content)->sum(function ($item) {
                        return is_array($item) ? ($item['quantity'] ?? 0) * ($item['cost'] ?? 0) : 0;
                    });
                } elseif (is_array($content)) {
                    // Handle single item structure
                    $itemTotal = ($content['quantity'] ?? 0) * ($content['cost'] ?? 0);
                } else {
                    $itemTotal = 0;
                }

                return $total + $itemTotal;
            }, 0);

            return [
                'total' => $totalAmount,
                'count' => $budgetsInState->count()
            ];
        })->toArray();
    }
}
