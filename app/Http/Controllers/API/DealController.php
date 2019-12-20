<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\Tariffa;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {

        $response = null; 
        $query = false;

        if ( $s = $request->query('s', false ) ) {

            $s = urldecode($s);

            $query = Deal::where('titolo', 'LIKE', '%' . $s . '%' )->orWhere( 'codice', 'LIKE', '%' . $s . '%' );

        } 
        
        if ( $notAttachedToServizi = $request->query('notAttachedToServizi', false ) ) { // Separati con la virgola

            if ( ! $query ) $query = Deal::whereDoesntHave('servizi' , function (Builder $query ) use ( $notAttachedToServizi )
            {
                return $query->whereIn('figlio' , explode(',' ,  $notAttachedToServizi ) );
            });

            else $query->whereDoesntHave('servizi' , function (Builder $query ) use ( $notAttachedToServizi )
            {
                return $query->whereIn('figlio' , explode(',' ,  $notAttachedToServizi ) );
            });

        } 

        if ( $query ) { 
            $response = $query->get(); 
        } else $response = Deal::all()->load('servizi');

        if ( $schema = $request->query( 'schema', false ) ) {

            $schemed = [];

            foreach ($response as $deal ) {
                if ( $schema == "select-2") {
                    $schemed[] = [ 'label' => $deal->titolo , 'value' => $deal->codice ];
                }
            }

            $response = $schemed;
        }

        return response($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Deal::class );

        $dati = $request->validate([
            'stato' => 'required|string|in:pubblico,privato,bozza',
            'titolo' => 'required|string',
            'descrizione' => 'nullable|string',
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

        $prodotto = new Deal($dati);
        
        $prodotto->save();

        $prodotto->tariffe = $dati['tariffe'];

        return response( $prodotto->load('servizi') , 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function show(Deal $deal)
    {
        return response( $deal->load('servizi') );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deal $deal)
    {
        $this->authorize('update', $deal); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deal $deal)
    {
        //
    }

    public function aggiungiTariffa(Request $request, Deal $deal)
    {
        $this->authorize('update', $deal); 

        $dati = $request->validate([
            'variante' => ['required', 'exists:varianti_tariffa,id' , Rule::unique('tariffe' , 'variante_tariffa_id')->where('prodotto_id' , $deal->id )],
            'imponibile' => 'required|int'
        ]);

        $deal->tariffe()->create(['variante_tariffa_id' => $dati['variante'] , 'imponibile' => $dati['imponibile']]);

        return response( $deal->load('servizi') , 201);
    }

    public function editTariffa( Request $request , Deal $deal , Tariffa $tariffa )
    {        
        $this->authorize('update', $deal); 

        if ( $tariffa->prodotto_id !== $deal->id ) return abort( 404, "Il prodotto non è associato a questa tariffa tariffa.");

        $d = $request->validate( ['imponibile' => 'required|int'] );

        $tariffa->imponibile = $d['imponibile'];

        $tariffa->save();
        
        return response( $deal->load('servizi') );
    }

    public function deleteTariffa( Request $request , Deal $deal , Tariffa $tariffa )
    {
        $this->authorize('update', $deal);  // TODO TariffaPolicy

        if ( $tariffa->prodotto_id !== $deal->id ) return abort( 404, "Il prodotto non è associato a questa tariffa tariffa.");

        $tariffa->delete();
        
        return response( $deal->load('servizi') );
    }
}
