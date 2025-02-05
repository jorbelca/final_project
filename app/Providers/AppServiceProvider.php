<?php

namespace App\Providers;

use App\Models\Budget;
use App\Models\Client;
use App\Models\Cost;
use App\Policies\BudgetViewPolicy;
use App\Policies\ClientViewPolicy;
use App\Policies\CostViewPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Cost::class, CostViewPolicy::class);
        Gate::policy(Client::class, ClientViewPolicy::class);
        Gate::policy(Budget::class, BudgetViewPolicy::class);
    }
}
