<?php

namespace App\Events\PayPal;

use App\Ordine;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * @property array $transazione;
 * @property array $notifica;
 * @property boolean $verified;
 * @property Ordine $ordine;
 * @property Ordine $ordine;
 */
class PaymentCapture
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notifica;

    public $ordine;

    public $verified;

    public $transazione;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($transazione, $verified = false, $notificaDaPayPal = null)
    {
        $this->verified = $verified;
        $this->notifica = $notificaDaPayPal;
        $this->transazione = $transazione;
        $this->ordine = Ordine::findOrFail($this->transazione['invoice_id']);
    }
}
