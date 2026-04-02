<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\BloodRequest::class => \App\Policies\BloodRequestPolicy::class,
    ];
    public function register(): void {}

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
