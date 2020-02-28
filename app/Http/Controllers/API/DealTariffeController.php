<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Deal;
use App\Tariffa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DealTariffeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Deal $deal)
    {
        return response( $deal->tariffe );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Deal $deal)
    {  
        $this->authorize('update', [ Tariffa::class , $deal ]);

        $dati = $request->validate([
            'variante' => [ 'required', 'exists:varianti_tariffa,id' , Rule::unique('tariffe' , 'variante_tariffa_id')->where('prodotto_id' , $deal->id )],
            'importo' => 'numeric|required'
        ]);

        $deal->tariffe()->create(['variante_tariffa_id' => $dati['variante'] , 'importo' => $dati['importo']]);

        return response( $deal->loadMissing('servizi') , 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function show(Deal $deal, Tariffa $tariffa)
    {        
        $this->authorize('show', [ $tariffa , $deal ]);

        if ( $tariffa->prodotto_id !== $deal->id ) return abort( 404, "Il prodotto non è associato a questa tariffa tariffa.");

        return response($tariffa);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deal $deal, Tariffa $tariffa)
    {
        $this->authorize('update', [ $tariffa , $deal ]);

        if ( $tariffa->prodotto_id !== $deal->id ) return abort( 404, "Il prodotto non è associato a questa tariffa.");

        // TODO può essere veramente nullable?

        $d = $request->validate( [ 
            'importo' => 'numeric|nullable' 
        ] );

        $tariffa->importo = $d['importo'];

        $tariffa->save();
        
        return response( $deal->loadMissing('servizi') );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deal $deal, Tariffa $tariffa)
    {
        $this->authorize('delete', [ $tariffa , $deal ]);

        if ( $tariffa->prodotto_id !== $deal->id ) 
            return abort( 404, "Il prodotto non è associato a questa tariffa tariffa.");

        $tariffa->forceDelete();

        return response( $deal->loadMissing('servizi') );
    }
}
