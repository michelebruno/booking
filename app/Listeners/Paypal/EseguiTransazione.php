<?php

namespace App\Listeners\Paypal;

use App\Events\Paypal\NuovaApprovazioneOrdine;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EseguiTransazione
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NuovaApprovazioneOrdine  $event
     * @return void
     */
    public function handle(NuovaApprovazioneOrdine $event)
    {
        //
    }
}
