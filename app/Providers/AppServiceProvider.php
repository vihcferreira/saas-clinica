<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\Organization;
use Illuminate\Support\ServiceProvider;
use App\Policies\OrganizationPolicy;
use App\Policies\AppointmentPolicy;

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
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }

    protected $policies = [
        Organization::class => OrganizationPolicy::class,
        Appointment::class => AppointmentPolicy::class,
    ];
}
