<?php

namespace App\Listeners;

use App\Events\OrdinePagato;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
        //
    }
}
