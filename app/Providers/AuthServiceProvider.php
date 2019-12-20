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
        'App\Models\Esercente' => 'App\Policies\EsercentePolicy',
        'App\Models\Servizio' => 'App\Policies\ServizioPolicy',
        'App\Models\Deal' => 'App\Policies\DealPolicy'
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
