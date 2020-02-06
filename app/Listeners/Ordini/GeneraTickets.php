<?php

namespace App\Listeners\Ordini;

use App\Events\OrdinePagato;
use App\Events\TicketsGenerati;
use App\Ticket;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class GeneraTickets implements ShouldQueue
{

    /**
     * 
     */
    public $tries = 3;
    
    /**
     * Handle the event.
     *
     * @param  OrdinePagato  $event
     * @return void
     */
    public function handle(OrdinePagato $event)
    {
        $ordine = $event->ordine;

        foreach ($ordine->voci as $voce ) {

            $giafatti = $voce->tickets->count();

            if ( $giafatti == $voce->quantita ) {
                break;
            }
            
            $tickets = [];

            /**
             * Genero i ticket per la voce corrente.
             */
            for ($i=0; $i < ( $voce->quantita - $giafatti ); $i++) { 

                $ticket = new Ticket();

                $ticket->stato = Ticket::APERTO;

                $ticket->prodotto_id = $voce->prodotto_id;

                $ticket->variante_tariffa_id = $voce->tariffa->variante_tariffa_id;

                $tickets[] = $ticket;

            }

            $voce->tickets()->saveMany($tickets);

        }

        event( new TicketsGenerati( $ordine ) );

    }
}
