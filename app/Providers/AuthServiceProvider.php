<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Features\Roles\Models\Role::class => \App\Features\Roles\Policies\RolePolicy::class,
        \App\Features\Logs\Models\Log::class => \App\Features\Logs\Policies\LogPolicy::class,
        \App\Features\Users\Models\User::class => \App\Features\Users\Policies\UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}

