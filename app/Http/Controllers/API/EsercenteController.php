<?php

namespace App\Http\Controllers\API;

use App\Models\Esercente;
use App\User;

use App\Models\UserMeta;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
        return response( Esercente::all() );
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Gate::authorize('create', Esercente::class);

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

        $metas = [];

        if( Arr::exists($dati, 'meta') ) {
        
            foreach($dati['meta'] as $key => $value) {
                if ( $value ) $metas[] = new UserMeta(["chiave" => $key, "valore" => $value]);
            }

            if ( count($metas) ) $user->meta()->saveMany($metas);

        }

        $user->sendEmailVerificationNotification();

        return response( $user );

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Esercente  $esercente
     * @return \Illuminate\Http\Response
     */
    public function show( $esercente)
    {
        return response( Esercente::findOrFail($esercente) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Esercente  $esercente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $esercente)
    {
        $user = Esercente::findOrFail($esercente);

        // TODO Validazione della richiesta
        $dati = $request->validate([
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->email, 'email')],
            'meta.*' => 'nullable',
            'indirizzo.*' => 'nullable',
            'username' => [ 'required', Rule::unique('users', 'username')->ignore($user->username, 'username')],
            'piva' => ['required', 'digits:11', Rule::unique('users', 'piva')->ignore($user->piva, 'piva')], // TODO verificare il formato
            'cf' => ['required', 'max:16' , Rule::unique('users', 'cf')->ignore($user->cf, 'cf')], // TODO verificare il formato,
            'nome' => 'string|required'
        ]);

        $user->fill($dati);

        $user->nome = $request->input('nome', false) ;

        $user->sdi = $request->input('sdi', false) ;

        $user->pec = $request->input('pec', false) ;

        $user->indirizzo = $request->input('indirizzo', false) ;

        $user->sede_legale = $request->input('sede_legale', false) ;

        $user->ragione_sociale = $request->input('ragione_sociale', false) ;

        $user->save();

        $metas = [];

        // if( Arr::exists($dati, 'meta') ) {
        
        //     foreach($dati['meta'] as $key => $value) {
        //         if ( $value ) $metas[] = new UserMeta(["chiave" => $key, "valore" => $value]);
        //     }

        //     if ( count($metas) ) $user->meta()->saveMany($metas);

        // }

        return response( $user );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Esercente  $esercente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Esercente $esercente)
    {
        //
    }
}
