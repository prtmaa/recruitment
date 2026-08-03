<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\JobApplication;
use App\Observers\JobApplicationObserver;

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
        JobApplication::observe(JobApplicationObserver::class);
    }
}
