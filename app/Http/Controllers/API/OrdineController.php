<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrdineRequest;
use App\Http\Resources\OrdineCollection;
use App\Http\Resources\OrdineResource;
use App\Models\Cliente;
use App\Models\Deal;
use App\Models\Tariffa;
use App\Models\VoceOrdine;
use App\Ordine;
use Illuminate\Http\Request;
use PayPal\Api\Order;
use PayPal\Rest\ApiContext;

class OrdineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        $request->validate(['per_page' => 'int']);

        $per_page = $request->query('per_page', 10);

        return response( Ordine::with(['cliente' , 'voci' ])->paginate($per_page) );

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\StoreOrdineRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrdineRequest $request)
    {
        $request->authorize();
        $dati = $request->validated();

        if ( in_array( $request->user()->ruolo , [ 'admin' , 'account_manager' ] ) ) {
            try {
                $cliente = Cliente::email( $dati["cliente"]["email"] );
            } catch (\Throwable $th) {
                $cliente = new Cliente($dati['cliente']);
                $cliente->save();
            }
        } else $cliente = $request->user();

        $voci = [];

        foreach ($dati['voci'] as $key => $voce) {

            $tariffa = Tariffa::findOrFail($voce['tariffa_id']);

            if ( $tariffa->prodotto->disponibili < $voce["qta"] ) {
                throw new \Exception("Il prodotto non è più disponibile."); //TODO ?
            }

            $v = new VoceOrdine();

            $v->tariffa_id = $voce['tariffa_id'];
            $v->quantita = $voce["qta"];

            // TODO ridurre i disponibili

            $voci[] = $v;

        }

        $ordine = new Ordine();

        $ordine->cliente()->associate($cliente);

        $ordine->save();

        $ordine->voci()->saveMany( $voci );

        $ordine->importo = $ordine->voci()->sum( 'importo' );

        $ordine->imponibile = round( $ordine->voci()->sum( 'imponibile' ), 2);

        $ordine->imposta = round( $ordine->voci()->sum( 'imposta' ) , 2 );

        $ordine->stato = 'pending';

        $ordine->data = date("Y-m-d");

        $ordine->saveOrFail();

        event( \App\Events\NuovoOrdine::class, $ordine );

        return response(Ordine::find($ordine->id)->load(['voci', 'cliente']), 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ordine  $ordine
     * @return \Illuminate\Http\Response
     */
    public function show(Ordine $ordine)
    {
        return response($ordine->load(['voci' , 'cliente']));
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
