<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\Servizio;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DealServizioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Deal $deal)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Deal $deal)
    {
        $dati = $request->validate([
            'servizio' => [
                'required',
                Rule::exists('prodotti', 'codice')->where('tipo', 'servizio')
            ]
        ]);

        $deal->servizi()->attach($dati['servizio']);


        return response($deal->load('servizi') , 201 );

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function show(Deal $deal, Servizio $servizio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deal $deal, Servizio $servizio)
    {
        // TODO authorize
        $dati = $request->validate(['servizi' => 'required|array']);
        $deal->servizi()->sync($dati['servizi']);

        if ( $request->query('from', false) == 'servizio') return response($servizio->load('deals'));

        return response($deal);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Deal $deal, Servizio $servizio)
    {
        // TODO verificare che 
        // TODO authorize
        
        $deal->servizi()->detach($servizio);

        if ( $request->query('from', false) == 'servizio') return response($servizio->load('deals'));

        return response($deal->load('servizi'));
    }
}
