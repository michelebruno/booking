<?php

namespace App\Providers;

use App\Setting;
use Illuminate\Support\Facades\DB;
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

        /**
         * Serve per creare con Blade le pagine di ApiDoc Generator.
         */
        if (!defined('URL'))
            define('URL', env('APP_URL'));
        if (!defined('token'))
            define('token', env('{{token}}'));

        $this->app->singleton(Setting::class, function () {

            $s = Setting::all();

            $n = new \stdClass();

            foreach ($s as $setting ) {
                $n[$s->chiave] = $setting;
            }

            return $n;
        } );


        $this->app->singleton('Tariffe', function ()
        {
            return \App\Tariffa::all()->keyBy('slug');
        });

        /**
         * @deprecated
         */
        $this->app->singleton('VariantiTariffe', function ()
        {
            return app("Tariffe");
        });

        $this->app->singleton('Prodotti', function ()
        {
            return \App\Prodotto::withTrashed()->get();
        });
        $this->app->singleton(\PayPal\Rest\ApiContext::class, function ()
        {
            return new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    config( 'services.paypal.client_id' ),     // ClientID
                    config( 'services.paypal.client_secret' )      // ClientSecret
                )
            );
        });

        $this->app->singleton(\PayPalCheckoutSdk\Core\PayPalHttpClient::class, function ()
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
        // Schema::defaultStringLength(191);

        DB::connection("mongodb")->enableQueryLog();

        \Laravel\Passport\Passport::routes();

        \App\Ordine::observe(\App\Observers\OrdineObserver::class);
        \App\VoceOrdine::observe(\App\Observers\VoceOrdineObserver::class);

        \App\Transazione::observe(\App\Observers\TransazioneObserver::class);
        \App\TransazionePayPal::observe(\App\Observers\TransazioneObserver::class);
    }
}
