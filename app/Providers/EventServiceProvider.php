<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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
        \App\Events\NuovoOrdine::class => [
            \App\Listeners\CreaOrdinePaypal::class,
        ],
        \App\Events\Paypal\NuovaApprovazioneOrdine::class => [
            \App\Listeners\Paypal\EseguiTransazione::class,
        ],
        \App\Events\NuovoPagamento::class => [
            \App\Listeners\InserisciTransazioni::class,
            \App\Listeners\AggiornaStatoOrdine::class
        ],
        \App\Events\OrdinePagato::class => [
            \App\Listeners\GeneraTickets::class,
            \App\Listeners\InviaTicketsMail::class
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
