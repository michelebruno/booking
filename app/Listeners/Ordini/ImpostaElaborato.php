<?php

namespace App\Listeners\Ordini;

use App\Events\TicketsGenerati;
use App\Ordine;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ImpostaElaborato
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
        $event->ordine->stato = Ordine::ELABORATO;
        $event->ordine->save();
    }
}
