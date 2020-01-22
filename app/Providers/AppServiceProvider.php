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

        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                config( 'services.paypal.client_id' ),     // ClientID
                config( 'services.paypal.client_secret' )      // ClientSecret
            )
        );
        
        $this->app->instance(\Paypal\Rest\ApiContext::class, $apiContext);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
