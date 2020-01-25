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
        $o = $transazione->ordine;

        $o->dovuto = $o->importo - $transazione->importo;
        
        if ( $o->dovuto == 0 && $transazione->verificata ) {
            $o->stato = 'pagato';
            $o->save();

            event( new OrdinePagato($o) );
        } elseif ( $o->dovuto == 0) {
            $o->stato = 'elaborazione_pagamento';
            $o->save();
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
        
        if ( $ordine->dovuto == 0 && $transazione->verificata && ! $ordine->stato === "pagato" ) { // ? e se è già stato esaurito?

            $ordine->stato = 'pagato';

            $ordine->saveOrFail();

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
