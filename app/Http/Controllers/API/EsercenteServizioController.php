<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Esercente;
use App\Models\Servizio;
use App\Models\Tariffa;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EsercenteServizioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Esercente $esercente )
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Esercente $esercente)
    {
        $this->authorize('create', Servizio::class );

        $dati = $request->validate([
            'stato' => 'required|string|in:pubblico,privato,bozza',
            'titolo' => 'required|string',
            'descrizione' => 'string',
            'disponibili' => 'integer',
            'codice' => 'required_if:codice_personalizzato,false|unique:prodotti',
            'iva' => 'integer|required',
            'tariffe' => 'array|bail',
            'tariffe.intero.imponibile' => 'required'
        ]);

        if ( $request->input( 'codice_personalizzato' , true ) ) $dati['codice'] = Str::random(10); // TODO

        $servizio = new Servizio($dati);

        $servizio->esercente_id = $esercente->id;
        
        $servizio->save();

        $servizio->tariffe = $request->input('tariffe');

        return response($servizio, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Esercente  $esercente
     * @param  int  $servizio
     * @return \Illuminate\Http\Response
     */
    public function show(Esercente $esercente, $servizio)
    {
        Servizio::di($esercente->id)->findOrFail($servizio);

        // TODO $this->authorize()

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Esercente  $esercente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Esercente $esercente, Servizio $servizio)
    {
        $this->authorize('create', Servizio::class );

        $dati = $request->validate([
            'stato' => 'string|in:pubblico,privato,bozza',
            'titolo' => 'string',
            'descrizione' => 'string',
            'disponibili' => 'integer',
            'codice' => Rule::unique('prodotti','codice')->ignore($servizio->id),
            'iva' => 'integer|',
            'tariffe' => 'array|bail',
            'tariffe.intero.imponibile' => ''
        ]);

        $servizio->fill($dati);
        
        $servizio->save();

        $servizio->tariffe = $request->input('tariffe', null);

        return response($servizio);
    }

    public function aggiungiTariffa(Request $request, Esercente $esercente, Servizio $servizio)
    {
        $dati = $request->validate([
            'variante' => ['required', 'exists:varianti_tariffa,id' , Rule::unique('tariffe' , 'variante_tariffa_id')->where('id' , $servizio->id )],
            'imponibile' => 'required|int'
        ]);

        $servizio->tariffe()->create(['variante_tariffa_id' => $dati['variante'] , 'imponibile' => $dati['imponibile']]);

        return response( $servizio , 201);
    }

    public function editTariffa(Request $request, Esercente $esercente, Servizio $servizio, Tariffa $tariffa)
    {
        $d = $request->validate(['imponibile' => 'required|int']);

        if ( ! $tariffa->prodotto_id === $servizio->id || ! $servizio->esercente_id === $esercente->id ) return abort(404);
        
        $tariffa->imponibile = $d['imponibile'];
        $tariffa->save();
        
        return response( $servizio );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Esercente  $esercente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Esercente $esercente, $servizio)
    {
        $servizio = Servizio::di($esercente->id)->findOrFail($servizio);

        // TODO $this->authorize();

        $servizio->delete();

        return response(null, 204);

    }
}
