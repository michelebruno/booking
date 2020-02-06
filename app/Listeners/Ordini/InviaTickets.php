<?php

namespace App\Listeners\Ordini;

use App\Events\TicketsGenerati;
use App\Mail\Ordini\RiepilogoMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class InviaTickets implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;
    
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
     * @param  TicketsGenerati  $event
     * @return void
     */
    public function handle(TicketsGenerati $event)
    {
        Mail::to( $event->ordine->cliente->email )->send( new RiepilogoMail( $event->ordine ) );
    }
}
