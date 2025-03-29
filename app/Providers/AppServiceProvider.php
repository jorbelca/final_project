<?php

namespace App\Providers;

use App\Models\Budget;
use App\Models\Client;
use App\Models\Cost;
use App\Models\Subscription;
use App\Models\Support;
use App\Policies\BudgetViewPolicy;
use App\Policies\ClientViewPolicy;
use App\Policies\CostViewPolicy;
use App\Policies\SubscriptionPolicy;
use App\Policies\SupportPolicy;
use Illuminate\Routing\UrlGenerator;
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
    public function boot(UrlGenerator $url): void
    {
        Gate::policy(Cost::class, CostViewPolicy::class);
        Gate::policy(Client::class, ClientViewPolicy::class);
        Gate::policy(Budget::class, BudgetViewPolicy::class);
        Gate::policy(Support::class, SupportPolicy::class);
        Gate::policy(Subscription::class, SubscriptionPolicy::class);

        if (env('APP_ENV') == 'production') {
            $url->forceScheme('https');
        }
    }
}
