<?php

namespace App\Http\Controllers\API;

use App\Models\Esercente;
use App\User;

use App\Models\UserMeta;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            'username' => [ 'required', 'unique:users'],
            'piva' => ['required', 'unique:users'], // TODO verificare il formato
            'cf' => ['required', 'unique:users'] // TODO verificare il formato
        ]);

        $user = new Esercente($dati);

        try {

            $api_token = Str::random(40);

            while ( User::where('api_token', $api_token)->count() ) {
                $api_token = Str::random(40);
            }

            $user->api_token = $api_token;

            $user->password = Hash::make($request->input('password'));
            
            $user->saveOrFail();

            $metas = [];

            if( Arr::exists($dati, 'meta') ) {
            
                foreach($dati['meta'] as $key => $value) {
                    if ( $value ) $metas[] = new UserMeta(["chiave" => $key, "valore" => $value]);
                }
    
                if ( count($metas) ) $user->meta()->saveMany($metas);

            }

            $user->sendEmailVerificationNotification();

            return response(User::toCamelCase($user::toArray()));

        } catch ( \Throwable $e ) {
            abort(500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Esercente  $esercente
     * @return \Illuminate\Http\Response
     */
    public function show(Esercente $esercente)
    {
        //
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
        //
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
