<?php

namespace App\Listeners;

use App\Events\OrdinePagato;
use App\Ticket;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class GeneraTickets
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
     * @param  OrdinePagato  $event
     * @return void
     */
    public function handle(OrdinePagato $event)
    {
        $ordine = $event->ordine;

        try {
            foreach ($ordine->voci as $voce ) {
                $tickets = [];
    
                /**
                 * Genero i ticket per la voce corrente.
                 */
                for ($i=0; $i < $voce->quantita; $i++) { 
    
                    $ticket = new Ticket();
    
                    $ticket->stato = "APERTO";
    
                    $ticket->prodotto_id = $voce->prodotto_id;
    
                    $ticket->variante_tariffa_id = $voce->tariffa->variante_tariffa_id;
    
                    $tickets[] = $ticket;
    
                }
    
                $voce->tickets()->saveMany($tickets);
            }

            
            
        } catch (\Throwable $th) {
            Log::error( 'Errore nel generare i tickets.', [ "exception" => $th, "evento" => $event ] );
        }


    }
}
