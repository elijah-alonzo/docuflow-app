<?php

namespace App\Providers;

use App\Features\Users\Models\User;
use App\Features\Logs\Observers\ModelActionObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        Schema::defaultStringLength(191);

        User::observe(ModelActionObserver::class);

        if (function_exists('view')) {
            view()->addLocation(app_path('Features/Workflows/Views'));
            view()->addLocation(app_path('Features/Approvals/Views'));
        }
    }
}
