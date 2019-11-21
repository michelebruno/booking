<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Servizio;
use Illuminate\Http\Request;

class ServizioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize( 'viewAny' , Servizio::class );

        return response( Servizio::all() );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Servizio::class );

        $dati = $request->validate([
            'stato' => 'required|string',
            'titolo' => 'required|string',
            'codice' => 'required|unique:prodotti',
            'esercente_id' => 'required|int' // Deve esistere ed essere esercente!
        ]);

        $servizio = new Servizio($dati);
        $servizio->save();

        return response($servizio, 201);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Servizio  $servizio
     * @return \Illuminate\Http\Response
     */
    public function show(Servizio $servizio)
    {
        $this->authorize('view', $servizio);

        return response( $servizio );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Servizio  $servizio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Servizio $servizio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Servizio  $servizio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Servizio $servizio)
    {
        //
    }
}
