<?php

namespace App\Listeners\Ordini;

use App\Events\TicketsGenerati;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ImpostaErogato
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
     * @param  TicketsGenerati  $event
     * @return void
     */
    public function handle(TicketsGenerati $event)
    {
        $event->ordine->stato = "ELABORATO";
        $event->ordine->save();
    }
}
