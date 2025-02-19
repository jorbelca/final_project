<?php

namespace App\Providers;

use App\Models\Budget;
use App\Models\Client;
use App\Models\Cost;
use App\Models\Incidencie;
use App\Policies\BudgetViewPolicy;
use App\Policies\ClientViewPolicy;
use App\Policies\CostViewPolicy;
use App\Policies\IncidenciesPolicy;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
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
    public function boot(UrlGenerator $url): void
    {
        Gate::policy(Cost::class, CostViewPolicy::class);
        Gate::policy(Client::class, ClientViewPolicy::class);
        Gate::policy(Budget::class, BudgetViewPolicy::class);
        Gate::policy(Incidencie::class, IncidenciesPolicy::class);

        if (env('APP_ENV') == 'production') {
            $url->forceScheme('https');
        }
       
    }
}
