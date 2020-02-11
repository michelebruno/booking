<?php

namespace App\Observers;

use App\VoceOrdine;

class VoceOrdineObserver
{
    /**
     * Handle the voce ordine "created" event.
     *
     * @param  \App\VoceOrdine  $voceOrdine
     * @return void
     */
    public function creating(VoceOrdine $voceOrdine)
    {
        //
    }
    /**
     * Handle the voce ordine "created" event.
     *
     * @param  \App\VoceOrdine  $voceOrdine
     * @return void
     */
    public function created(VoceOrdine $voceOrdine)
    {
        $voceOrdine->riduciDisponibili( $voceOrdine->quantita );
    }

    /**
     * Handle the voce ordine "updated" event.
     *
     * @param  \App\VoceOrdine  $voceOrdine
     * @return void
     */
    public function updated(VoceOrdine $voceOrdine)
    {
        //
    }

    /**
     * Handle the voce ordine "deleted" event.
     *
     * @param  \App\VoceOrdine  $voceOrdine
     * @return void
     */
    public function deleted(VoceOrdine $voceOrdine)
    {
        //
    }

    /**
     * Handle the voce ordine "restored" event.
     *
     * @param  \App\VoceOrdine  $voceOrdine
     * @return void
     */
    public function restored(VoceOrdine $voceOrdine)
    {
        //
    }

    /**
     * Handle the voce ordine "force deleted" event.
     *
     * @param  \App\VoceOrdine  $voceOrdine
     * @return void
     */
    public function forceDeleted(VoceOrdine $voceOrdine)
    {
        //
    }
}
