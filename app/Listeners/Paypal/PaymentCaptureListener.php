<?php

namespace App\Listeners\Paypal;

use App\Events\PayPal\PaymentCapture;
use App\Models\TransazionePaypal;
use App\Ordine;
use Illuminate\Support\Facades\Log;

class PaymentCaptureListener
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
     * @param  PaymentCapture  $event
     * @return void
     */
    public function handle( PaymentCapture $event )
    {
        Log::info("Ascolto l'evento");
        if ( $transazione = TransazionePaypal::transazioneId( $event->transazione['id'] )->first() ) { // Verifico la transazione effettuata
            Log::info('La transazione esiste già ', [ "transazione" => $transazione , "event" => $event ]);
            
        } else {
            Log::info( 'Creo nuova transazione: ' . $event->transazione['id'] );

            $transazione = new TransazionePaypal();
            // TODO check ordine

            $transazione->importo = $event->transazione['amount']['value'];

            $transazione->transazione_id = $event->transazione['id'];

            $transazione->ordine_id = Ordine::findOrFail($event->transazione['invoice_id'])['id'];

            if ($event->verified) {

                $transazione->verified_by_event_id = $event->notifica['id'];

            }

            $transazione->saveOrFail();
        }
    }
}
