<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Plat;
use App\Policies\PlatPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected $policies = [
        Plat::class => PlatPolicy::class,
        // Add more model => policy pairs if needed
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Optional: You can define gates here if needed

        Gate::define('admin-only', function ($user) {
            return $user->role === 'admin';
        });
    }
}