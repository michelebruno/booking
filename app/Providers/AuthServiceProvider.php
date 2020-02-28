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
        'App\Esercente' => 'App\Policies\EsercentePolicy',
        'App\Servizio' => 'App\Policies\ServizioPolicy',
        'App\Deal' => 'App\Policies\DealPolicy',
        'App\Tariffa' => 'App\Policies\TariffaPolicy',
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
