<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Deal; 
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
        $request->validate([
            'per_page' => 'int',
            'order' => 'in:asc,desc',
            'order_by' => 'in:created_at,updated_at,codice,titolo,disponibili'
        ]);

        $per_page = (int) $request->query('per_page', 10);

        $query =  Deal::with( [] );

        $query->orderBy( $request->input('order_by', 'created_at') , $request->input('order', 'desc') );

        if ( $s = $request->query('s', false ) ) {

            $s = urldecode($s);

            $query = $query->where('titolo', 'LIKE', '%' . $s . '%' )->orWhere( 'codice', 'LIKE', '%' . $s . '%' );

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
 
        return response( $query->paginate($per_page) );
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @todo  De
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
            'tariffe' => 'array|required|bail',
            'tariffe.intero.imponibile' => 'sometimes|numeric',
            'tariffe.intero.importo' => 'required_if:tariffe.intero.imponibile,null|numeric'
        ]);
 
        // TODO Creare un codice non random ma unico
        //  ? Il valore di dafault deve essere true?

        if ( $request->input( 'codice_personalizzato' , true ) ) $dati['codice'] = Str::random(10);

        $prodotto = new Deal($dati);
        
        $prodotto->save();

        $prodotto->tariffe = $dati['tariffe'];

        $prodotto->save();

        return response( $prodotto->load('servizi') , 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function show($deal)
    {
        $deal = Deal::withTrashed()->whereCodice($deal)->firstOrFail();

        $this->authorize( 'view', $deal );

        return response( $deal->load('servizi') );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deal $deal)
    {
        $this->authorize('update', $deal); 

        $dati = $request->validate([
            'stato' => 'string|in:pubblico,privato,bozza',
            'titolo' => 'string',
            'descrizione' => 'string',
            'disponibili' => 'integer',
            'codice' => Rule::unique('prodotti', 'codice')->ignore($deal->id),
            'iva' => 'integer'
        ]);

        $deal->fill($dati);
        
        $deal->save();

        return response($deal->load('servizi') );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deal $deal)
    {
        $this->authorize('delete', $deal);

        if ( $deal->delete() ) {

            return response( $deal );
            
        }
    }

    public function restore($deal)
    {
        $this->authorize('restore', Deal::class);

        $deal = Deal::onlyTrashed()->whereCodice($deal)->firstOrFail();

        if ( $deal->restore() ) {
            return response( $deal->load('servizi') );
        } else abort(500);
    }

}
