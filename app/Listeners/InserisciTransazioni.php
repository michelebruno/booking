<?php

namespace App\Listeners;

use App\Events\NuovoPagamento;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class InserisciTransazioni
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
     * @param  NuovoPagamento  $event
     * @return void
     */
    public function handle(NuovoPagamento $event)
    {
        //
    }
}
