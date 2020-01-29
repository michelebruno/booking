<?php

namespace App\Providers;

use App\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Setting::class, function ($app) {
            
            $s = Setting::all();

            $n = new \stdClass();

            foreach ($s as $setting ) {
                $n[$s->chiave] = $setting;
            }

            return $n;
        } ); 
        
        $this->app->singleton(\PayPal\Rest\ApiContext::class, function ( $app )
        {
            return new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    config( 'services.paypal.client_id' ),     // ClientID
                    config( 'services.paypal.client_secret' )      // ClientSecret
                )
            );
        });


        $this->app->singleton(\PayPalCheckoutSdk\Core\PayPalHttpClient::class, function ($app)
        {
            $environment = new \PayPalCheckoutSdk\Core\SandboxEnvironment(
                config( 'services.paypal.client_id' ),     // ClientID
                config( 'services.paypal.client_secret' )      // ClientSecret
            );

            return new \PayPalCheckoutSdk\Core\PayPalHttpClient($environment);
        });

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        \App\Ordine::observe(\App\Observers\OrdineObserver::class);

        \App\Transazione::observe(\App\Observers\TransazioneObserver::class);
        \App\TransazionePayPal::observe(\App\Observers\TransazioneObserver::class);
    }
}
