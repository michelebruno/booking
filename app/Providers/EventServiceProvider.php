<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \App\Events\PayPal\PaymentCapture::class => [
            \App\Listeners\Paypal\PaymentCaptureListener::class
        ],
        \App\Events\OrdinePagato::class => [
            \App\Listeners\GeneraTickets::class
        ],
        \App\Events\TransazioneCreata::class => [
            \App\Listeners\AggiornaOrdineDopoTransazione::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
