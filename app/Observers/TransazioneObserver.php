<?php

namespace App\Observers;

use App\Events\OrdinePagato;
use App\Transazione;
use Illuminate\Support\Facades\Log;

class TransazioneObserver
{
    /**
     * Handle the transazione "created" event.
     *
     * @param  \App\Transazione  $transazione
     * @return void
     */
    public function created(Transazione $transazione)
    {
        $ordine = $transazione->ordine;
        Log::info('Ascolto Transazione, linea '. __LINE__);

        $ordine->dovuto = $ordine->importo - $transazione->importo;
        
        if ( $ordine->dovuto == 0 && $transazione->verificata && $transazione->stato === "COMPLETED" ) { // TODO e se era in elaborazione?

            $ordine->stato = 'PAGATO';

            $ordine->save();

            event( new OrdinePagato($ordine) );

        } elseif ( $ordine->dovuto == 0) {

            $ordine->stato = "ELABORAZIONE";

            $ordine->save();
            
        }
    }

    /**
     * Handle the transazione "updated" event.
     *
     * @param  \App\Transazione  $transazione
     * @return void
     */
    public function updated(Transazione $transazione)
    {
        $ordine = $transazione->ordine;

        Log::info('Ascolto Transazione, linea '. __LINE__);

        if ( $ordine->dovuto == 0 && $transazione->verificata && $transazione->stato === "COMPLETED" && $ordine->stato !== "PAGATO" ) { // ? e se è già stato esaurito?

            $ordine->stato = "PAGATO";

            $ordine->saveOrFail();

            Log::info('Ascolto Transazione, linea '. __LINE__);

            event( new OrdinePagato($ordine) );
        } 
    }

    /**
     * Handle the transazione "deleted" event.
     *
     * @param  \App\Transazione  $transazione
     * @return void
     */
    public function deleted(Transazione $transazione)
    {
        //
    }

    /**
     * Handle the transazione "restored" event.
     *
     * @param  \App\Transazione  $transazione
     * @return void
     */
    public function restored(Transazione $transazione)
    {
        //
    }

    /**
     * Handle the transazione "force deleted" event.
     *
     * @param  \App\Transazione  $transazione
     * @return void
     */
    public function forceDeleted(Transazione $transazione)
    {
        //
    }
}
