<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use App\Http\Controllers\SubscriptionController;
use App\Models\Plan;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);

        Vite::prefetch(concurrency: 3);

        Jetstream::inertia()->whenRendering(
            'Profile/Show',
            function ($request, array $data) {
                return array_merge($data, [
                    'Subscription' => SubscriptionController::getUserSubscription($request->user()),
                    'Plans' => Plan::all(),
                ]);
            }
        );
    }

    /**
     * Configure the permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}
