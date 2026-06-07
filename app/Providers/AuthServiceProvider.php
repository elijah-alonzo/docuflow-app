<?php

namespace App\Providers;

use App\Models\Load;
use App\Models\User;
use App\Policies\LoadPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\AcademicYear::class => \App\Policies\AcademicYearPolicy::class,
        \App\Models\Load::class => \App\Policies\LoadPolicy::class,
        \App\Models\Program::class => \App\Policies\ProgramPolicy::class,
        \App\Models\RegistrationRequest::class => \App\Policies\RegistrationRequestPolicy::class,
        \Spatie\Permission\Models\Role::class => \App\Policies\RolePolicy::class,
        \App\Models\Subject::class => \App\Policies\SubjectPolicy::class,
        \App\Models\SystemLog::class => \App\Policies\SystemLogPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}

