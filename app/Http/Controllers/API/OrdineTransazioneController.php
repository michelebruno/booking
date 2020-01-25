<?php

namespace App\Http\Controllers\API;

use App\Events\Paypal\PaymentCapture;
use App\Http\Controllers\Controller;
use App\Models\TransazionePayPal;
use App\Ordine;
use Illuminate\Http\Request;

class OrdineTransazioneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storePayPal(Request $request, Ordine $ordine)
    {
       /*  $dati = $request->validate([
            'id' => 'required|string',
            'intent' => 'required|in:CAPTURE',
            'purchase_units.0.amount.value' => 'required|numeric',
            'purchase_units.0.amount.currency_code' => 'required|in:EUR',
            'purchase_units.0.payments.captures.*' => 'required|in:EUR',
            'purchase_units.0.invoice_id' => 'required|string|in:' . $ordine->id,
        ]); */


        event( new PaymentCapture( $request->all() ) );
        
        return response( $ordine->fresh()->completo() );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ordine  $ordine
     * @return \Illuminate\Http\Response
     */
    public function show(Ordine $ordine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ordine  $ordine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ordine $ordine)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ordine  $ordine
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ordine $ordine)
    {
        //
    }
}
