<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Listeners\NotifyEligibleDonors;
use App\Events\BloodRequestCreated;


class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        BloodRequestCreated::class => [
            NotifyEligibleDonors::class,
        ],
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
