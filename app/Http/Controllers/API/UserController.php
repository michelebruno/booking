<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUser;
use App\Http\Resources\UserResource;
use App\User;
use App\Models\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        return ( new UserResource( User::all()->whereIn('ruolo', ['admin', 'account_manager']) ) );

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUser $request)
    {
        $dati = $request->validated();

        $user = new User($dati);

        try {
            $api_token = Str::random(40);
            while ( User::where('api_token', $api_token)->count() ) {
                $api_token = Str::random(40);
            }
            $user->api_token = $api_token;
            
            $user->saveOrFail();

            $metas = [];
            
            foreach($dati['meta'] as $key => $value) {
                if ( $value ) $metas[] = new UserMeta(["chiave" => $key, "valore" => $value]);
            }

            if ( count($metas) ) $user->meta()->saveMany($metas);

            //$user->sendEmailVerificationNotification();
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
        $this->authorize('view', User::class);

        return response(new UserResource( User::findOrFail($id) ));
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
        //
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
