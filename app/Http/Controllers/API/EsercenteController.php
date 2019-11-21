<?php

namespace App\Http\Controllers\API;

use App\Models\Esercente;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EsercenteController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Esercente::class);
        
        return response( Esercente::withTrashed()->get() );
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->authorize('create', Esercente::class);

        // TODO Validazione della richiesta
        $dati = $request->validate([
            'email' => ['required', 'email', 'unique:users'],
            'meta.*' => 'nullable',
            'indirizzo.*' => 'nullable',
            'username' => [ 'required', 'unique:users'],
            'piva' => ['required', 'digits:11', 'unique:users'], // TODO verificare il formato
            'cf' => ['required', 'max:16', 'unique:users'], // TODO verificare il formato,
            'nome' => 'string|required'
        ]);

        $user = new Esercente($dati);

        $api_token = Str::random(40);

        while ( User::where('api_token', $api_token)->count() ) {
            $api_token = Str::random(40);
        }

        $user->api_token = $api_token;

        $user->password = Hash::make( $request->input('password') );
        
        $user->saveOrFail();

        $user->nome = $request->input('nome') ;

        $user->sdi = $request->input('sdi', false) ;

        $user->pec = $request->input('pec', false) ;

        $user->indirizzo = $request->input('indirizzo', false) ;

        $user->sede_legale = $request->input('sede_legale', false) ;

        $user->ragione_sociale = $request->input('ragione_sociale', false) ;

        $user->save();

        $user->markEmailAsVerified();

        // $metas = [];

        // TODO Salvare i meta

        // if( Arr::exists($dati, 'meta') ) {
        
        //     foreach($dati['meta'] as $key => $value) {
        //         if ( Arr::exists( $user->meta , $key ) ) { // Aggiorniamo il metadato

        //         } else { // Creiamo il metadato
                    
        //         }
        //         if ( $value ) $metas[] = new UserMeta(["chiave" => $key, "valore" => $value]);
        //     }

        //     if ( count($metas) ) $user->meta()->saveMany($metas);

        // }

        // $user->sendEmailVerificationNotification();

        return response( $user );

    }

    /**
     * Display the specified resource.
     *
     * @param  int $esercente
     * @return \Illuminate\Http\Response
     */
    public function show( $esercente )
    {
        $esercente = Esercente::withTrashed()->findOrFail($esercente);

        $this->authorize( 'view', $esercente );
        
        return response( $esercente );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Esercente  $esercente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Esercente $esercente)
    {
        $this->authorize( 'update' , $esercente );

        // TODO Validazione della richiesta

        $dati = $request->validate([
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($esercente->email, 'email')],
            'meta.*' => 'nullable',
            'indirizzo.*' => 'nullable',
            'username' => [ 'required', Rule::unique('users', 'username')->ignore($esercente->username, 'username')],
            'piva' => ['required', 'digits:11', Rule::unique('users', 'piva')->ignore($esercente->piva, 'piva')], 
            'cf' => ['required', 'max:16' , Rule::unique('users', 'cf')->ignore($esercente->cf, 'cf')],
            'nome' => 'string|required'
        ]);

        $esercente->fill($dati); // TODO: quanto è sicuro? L'attributo fillable come è impostato?

        $esercente->nome = $request->input('nome', false) ;

        $esercente->sdi = $request->input('sdi', false) ;

        $esercente->pec = $request->input('pec', false) ;

        $esercente->indirizzo = $request->input('indirizzo', false) ;

        $esercente->sede_legale = $request->input('sede_legale', false) ;

        $esercente->ragione_sociale = $request->input('ragione_sociale', false) ;

        $esercente->save();

        $metas = [];

        // TODO Metadati

        // if( Arr::exists($dati, 'meta') ) {
        
        //     foreach($dati['meta'] as $key => $value) {
        //         if ( $value ) $metas[] = UserMeta::updateOrCreate( ["chiave" => $key , "user_id" => $esercente->id ], [  "valore" => $value ] );
        //     }

        //     if ( count($metas) ) $esercente->meta()->saveMany($metas);

        // }

        return response( $esercente );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Esercente  $esercente
     * @return \Illuminate\Http\Response
     */
    public function destroy( Esercente $esercente)
    {
        $this->authorize('delete', $esercente );

        $esercente->delete();
    
        return response( $esercente, 200 );
            
    }

    public function restore( $esercente )
    {
        $this->authorize( 'restore', $esercente = Esercente::withTrashed()->findOrFail( $esercente ) );  // TODO oppure onlyTrashed?

        if ( $esercente->restore() ) {
            return response( $esercente );
        } else abort(400);
    }

    public function setNote( Esercente $esercente , Request $request )
    {
        // TODO : authorize
        // TODO : e per gli altri campi?
        $dati = $request->validate([
            'note' => 'required|string'
        ]);

        $esercente->note = $dati['note'];
        
        $esercente->save();

        return response($esercente);

    }

}
