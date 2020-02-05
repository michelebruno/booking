<?php

namespace App\Listeners\Paypal;

use App\Events\PayPal\PaymentCapture;
use App\TransazionePayPal;
use App\Ordine;
use Illuminate\Support\Facades\Log;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalHttp\HttpException;

class PaymentCaptureListener
{
    public $ordine;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct( )
    {

    }

    /**
     * Handle the event.
     *
     * @param  PaymentCapture  $event
     * @return void
     */
    public function handle( PaymentCapture $event )
    {
        $this->ordine = $event->ordine;

        $transazione = TransazionePayPal::transazioneId( $event->transazione['id'] )->first();

        if ( $transazione && $transazione->importo == $event->transazione["amount"]["value"] ) { // Verifico la transazione effettuata

            if ( $transazione->stato !== $event->transazione['status'] ) {
                $transazione->stato = $event->transazione['status'];
            }

            if ( $event->verified && ! $transazione->verificata ) {
                $transazione->verified_by_event_id = $event->notifica["id"];
            } 

            $transazione->save();
            
        } else {

            if ( $transazione ) {
                Log::alert("L'importo della transazione non era congruo.");
                // TODO resettare transazione
                $transazione->delete();
            }

            $transazione = new TransazionePayPal();
            // TODO check ordine

            $transazione->importo = $event->transazione['amount']['value'];

            $transazione->stato = $event->transazione['status'];

            $transazione->transazione_id = $event->transazione['id'];

            $transazione->ordine_id = Ordine::findOrFail( $event->transazione['invoice_id'] )['id'];

            if ($event->verified) {

                $transazione->verified_by_event_id = $event->notifica['id'];

            }

            $transazione->saveOrFail();

        }
    }
    
}
