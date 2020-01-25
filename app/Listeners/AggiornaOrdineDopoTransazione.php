<?php

namespace App\Listeners;

use App\Events\TransazioneCreata;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AggiornaOrdineDopoTransazione
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
     * @param  TransazioneCreata  $event
     * @return void
     */
    public function handle(TransazioneCreata $event)
    {
        //
    }
}
