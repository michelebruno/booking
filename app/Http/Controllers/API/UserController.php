<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUser;
use App\Http\Resources\UserResource;
use App\User;
use App\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        $this->authorize('viewAny', User::class);

        $per_page = $request->query("per_page", 10);

        $query = User::whereIn('ruolo', ['admin', 'account_manager']);

        $query->orderBy( $request->input('order_by', 'created_at') , $request->input('order', 'desc') );
        
        return response( $query->paginate($per_page) );

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUser $request)
    {
        $this->authorize('create', User::class);

        $dati = $request->validated();

        $user = new User($dati);

        try {
            
            $user->password = Hash::make($request->input('password'));
            
            $user->saveOrFail();

            // $metas = [];
            
            // foreach($dati['meta'] as $key => $value) {
            //     if ( $value ) $metas[] = new UserMeta(["chiave" => $key, "valore" => $value]);
            // }

            // if ( count($metas) ) $user->meta()->saveMany($metas);

            $user->markEmailAsVerified();

            return response(new UserResource($user));
        } catch ( \Throwable $e ) {
            abort(500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        $this->authorize('view', $user);

        return response(new UserResource( $user ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update', User::findOrFail($id) );

        if ( $request->user()->ruolo !== 'admin' && $request->input( 'ruolo' ) == 'admin') abort(403);

        $user = User::updateOrCreate( ["id" => $id], $request->only( ( new User() )->getFillable() ));

        $user->nome = $request->input('nome', false);

/*         if ( $request->has('meta') ) {
            foreach ($request->input('meta') as $chiave => $valore) {
                $user->meta()->updateOrCreate(["chiave" => $chiave], ["valore" => $valore]);
            }
        }  */
        return response( new UserResource($user) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ( $user = User::findOrFail($id)) {
            $user->delete();
            return response()->json(['message' => 'Eliminato']);
        }
    }
}
