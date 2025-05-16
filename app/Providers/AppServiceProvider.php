<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        $this->registerPolicies();

    Gate::define('is-staff', function ($user) {
        return $user->role === 'staff';
    });

    Gate::define('is-customer', function ($user) {
        return $user->role === 'customer';
    });

    Gate::define('view-customer-dashboard', function ($user) {
        return $user->role === 'customer';
    });

    Gate::define('view-staff-dashboard', function ($user) {
        return $user->role === 'staff';
    });
    }
}
