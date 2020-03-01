<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrdineRequest;
use App\Http\Resources\OrdineCollection;
use App\Http\Resources\OrdineResource;
use App\Cliente;
use App\Deal;
use App\Tariffa;
use App\VoceOrdine;
use App\Ordine;
use App\User;
use Illuminate\Http\Request;

class OrdineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        $request->validate([
            'per_page' => 'int',
            'order' => 'in:asc,desc',
            'order_by' => 'in:created_at,updated_at,id,importo,imponibile,email'
        ]);

        $per_page = $request->query('per_page', 10);

        $query = Ordine::with([ 'cliente' , 'voci', 'transazioni' ]);

        $query->orderBy( $request->input('order_by', 'created_at') , $request->input('order', 'desc') );

        switch ( strtoupper( $request->query('stato', false ) ) ) {
            case 'PAGATI':
                $query->whereIn("stato", [ Ordine::ELABORAZIONE, Ordine::PAGATO, Ordine::ELABORATO ]);
                break;

            case 'APERTI':
                $query->where("stato", Ordine::APERTO );
                break;
            default: 
                break;
        }

        if ( !$per_page ) {
            return response( $query->get() );
        }

        return response( $query->paginate($per_page) );

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\StoreOrdineRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrdineRequest $request)
    {
        // TODO authorize
        $request->authorize();

        $dati = $request->validated();

        if ( in_array( $request->user()->ruolo , [ User::RUOLO_ACCOUNT , User::RUOLO_ADMIN ] ) ) {
            try {
                $cliente = Cliente::whereEmail( $dati["cliente"]["email"] )->firstOrFail();
            } catch (\Throwable $th) {
                $cliente = new Cliente($dati['cliente']);
                $cliente->save();
            }
        } else $cliente = $request->user();

        $voci = [];

        foreach ($dati['voci'] as $voce) {

            $tariffa = Tariffa::findOrFail($voce['tariffa_id']);

            if ( $tariffa->prodotto->disponibili < $voce["qta"] ) {
                throw new \Exception("Il prodotto non è più disponibile."); //TODO ?
            }

            $v = new VoceOrdine();

            $v->tariffa_id = $voce['tariffa_id'];
            $v->quantita = $voce["qta"];

            $voci[] = $v;

        }

        $ordine = new Ordine();

        $ordine->cliente()->associate($cliente);

        $ordine->save();

        $ordine->voci()->saveMany( $voci );

        $ordine->calcola();

        $ordine->saveOrFail();

        return response( $ordine->fresh()->completo(), 201 );

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ordine  $ordine
     * @return \Illuminate\Http\Response
     */
    public function show(Ordine $ordine)
    {
        $this->authorize('view', $ordine );
        
        return response( $ordine->completo() );
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
