<?php

namespace App\Events;

use App\Ordine;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * 
 * 
 * @property Ordine $ordine L'ordine che è stato appena pagato.
 */
class OrdinePagato
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ordine;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( Ordine $ordine )
    {
        $this->ordine = $ordine;
    }

}
