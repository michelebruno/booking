<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Cliente' => 'App\Policies\ClientePolicy',
        'App\Fornitore' => 'App\Policies\FornitorePolicy',
        'App\Fornitura' => 'App\Policies\FornituraPolicy',
        'App\Deal' => 'App\Policies\DealPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
