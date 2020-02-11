<?php

namespace App\Listeners\Ordini;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RiduciDisponibili implements ShouldQueue
{
    use InteractsWithQueue;

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
     * @param  \App\Events\OrdinePagato  $event
     * @return void
     */
    public function handle($event)
    {
        $ordine = $event->ordine;

        foreach ($ordine->voci as $voce ) {

            $voce->prodotto->riduciDisponibili($voce->quantità);

        }
    }
}
