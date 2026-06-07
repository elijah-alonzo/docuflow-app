<?php

namespace App\Providers;

use App\Models\Load;
use App\Models\Program;
use App\Models\Subject;
use App\Models\User;
use Livewire\Livewire;
use App\Observers\ModelActionObserver;
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

        Load::observe(ModelActionObserver::class);
        Program::observe(ModelActionObserver::class);
        Subject::observe(ModelActionObserver::class);
        User::observe(ModelActionObserver::class);
        Livewire::component('grading-sheet-manager', \App\App\Livewire\GradingSheetManager::class);
    }
}
