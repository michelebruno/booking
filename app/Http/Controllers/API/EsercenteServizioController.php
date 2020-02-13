<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Esercente;
use App\Servizio;
use App\Tariffa;
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
    public function index( Request $request, Esercente $esercente )
    {
        $this->authorize('view', $esercente);

        $per_page = $request->query('per_page' , 10 );

        $query = Servizio::fornitore($esercente->id)->with('deals');

        if ( $request->query('only_trashed' , false ) )
            $query->onlyTrashed();
        elseif ( $request->query('with_trashed' , false ) ) 
            $query->withTrashed();
        
        return response( $query->paginate( $per_page ) ); 
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
            'tariffe.intero.imponibile' => 'required',
            'tariffe.*.imponibile' => 'required'
        ]);

 
        // TODO Creare un codice non random ma unico
        //  ? Il valore di dafault deve essere true?

        if ( $request->input( 'codice_personalizzato' , true ) ) $dati['codice'] = Str::random(10);

        $servizio = new Servizio($dati);

        $servizio->esercente_id = $esercente->id;
        
        $servizio->save();

        // TODO impostare 
        $servizio->tariffe = $request->input('tariffe');

        return response($servizio->load('deals') , 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Esercente  $esercente
     * @param  int  $servizio
     * @return \Illuminate\Http\Response
     */
    public function show(Esercente $esercente, Servizio $servizio)
    {
        if ( $esercente->id !== $servizio->esercente_id ) abort(404, 'Questo servizio non è associato a questo esercente.');

        $this->authorize('view' , $servizio) ;

        return response( $servizio->load('deals') );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Esercente  $esercente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Esercente $esercente, Servizio $servizio)
    {
        if ( $esercente->id !== $servizio->esercente_id ) abort(404, 'Questo servizio non è associato a questo esercente.');

        $this->authorize('create', $servizio );

        if ( $request->user()->ruolo == 'esercente' ) {

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

        } else {

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

        }

        $servizio->fill($dati);
        
        $servizio->save();

        $servizio->tariffe = $request->input('tariffe', null);

        return response( $servizio->load('deals') );
    }

    public function aggiungiTariffa(Request $request, Esercente $esercente, Servizio $servizio)
    {
        if ( $esercente->id !== $servizio->esercente_id ) abort(404, 'Questo servizio non è associato a questo esercente.');
        
        $this->authorize('update' , $servizio );
        
        $dati = $request->validate([
            'variante' => ['required', 'exists:varianti_tariffa,id' , Rule::unique('tariffe' , 'variante_tariffa_id')->where('prodotto_id' , $servizio->id )],
            'imponibile' => 'required|int'
        ]);

        $servizio->tariffe()->create(['variante_tariffa_id' => $dati['variante'] , 'imponibile' => $dati['imponibile']]);

        return response( $servizio->load('deals') , 201);
    }

    public function editTariffa(Request $request, Esercente $esercente, Servizio $servizio, Tariffa $tariffa)
    {
        if ( $esercente->id !== $servizio->esercente_id ) abort(404, 'Questo servizio non è associato a questo esercente.');

        $this->authorize('update' , $servizio );

        $d = $request->validate(['imponibile' => 'required|int']);

        if ( $tariffa->prodotto_id !== $servizio->id ) return abort(404, "L'id del prodotto non è associato a questa tariffa tariffa.");
        
        $tariffa->imponibile = $d['imponibile'];
        $tariffa->save();
        
        return response( $servizio->load('deals')  );
    }

    public function deleteTariffa(Request $request, Esercente $esercente, Servizio $servizio, Tariffa $tariffa)
    {
        if ( $esercente->id !== $servizio->esercente_id ) abort(404, 'Questo servizio non è associato a questo esercente.');
        if ( $tariffa->prodotto_id !== $servizio->id ) return abort(404, "L'id del prodotto non è associato a questa tariffa tariffa.");

        $this->authorize('update' , $servizio );
        // TODO $this->authorize('delete' , $tariffa );

        $tariffa->delete();
        
        return response( $servizio->load('deals')  );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Esercente  $esercente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Esercente $esercente, Servizio $servizio)
    {        
        $this->authorize('delete' , $servizio );

        if ( $esercente->id !== $servizio->esercente_id ) abort(404);

        $servizio->delete();

        return response(null, 204);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Esercente  $esercente
     * @return \Illuminate\Http\Response
     */
    public function restore(Esercente $esercente, $servizio)
    {        
        $this->authorize('restore' , $servizio );

        $servizio = Servizio::fornitore($esercente->id)->onlyTrashed()->findOrFail($servizio);

        $servizio->restore();

        return response($servizio->load('deals') );

    }
}
