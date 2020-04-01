<?php

namespace App\Http\Controllers\API;

use App\Fornitore;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUser;
use App\Http\Resources\UserResource;
use App\User;
use App\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @todo Impostare i filtri
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $request->validate([
            "per_page" => ["integer", "nullable"],
            "order" => ["nullable", "in:asc,desc"],
            "order_by" => ["nullable", "string"],
            "ruolo" => ["nullable", Rule::in(User::RUOLI)],
        ]);

        $per_page = $request->query("per_page", 10);

        if ($ruolo = $request->query("ruolo", false)) {
            $query = User::whereRuolo($ruolo);
        } else {
            $query = User::admin();
        }

        $query->orderBy($request->input('order_by', 'created_at'), $request->input('order', 'desc'));

        return response($query->paginate($per_page));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $dati = $request->validate([
            'email' => ['required', 'email', 'unique:users'],
            'ruolo' => ['required', Rule::in(\App\User::RUOLI)],
            'password' => ['required', 'confirmed'],
            'nome' => 'nullable',
            'cognome' => 'nullable',
            'username' => ['required', 'unique:users', 'not_regex:/^.+@.+$/i']
        ]);

        if (
            $request->input('ruolo') == 'admin' &&
            !$request->user()->isSuperAdmin()
        ) abort(403, 'Non hai i permessi per creare un amministratore.');

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
        } catch (\Throwable $e) {
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

        return response(new UserResource($user));
    }

    public function showCurrent()
    {

        $user = request()->user();

        if ($user->ruolo == User::RUOLO_FORNITORE) {
            return response(Fornitore::findOrFail($user->id));
        }

        return response($user);
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
        $this->authorize('update', User::findOrFail($id));

        if (!$request->user()->isSuperAdmin() && $request->input('ruolo') == 'admin') abort(403, 'Non hai i permessi per creare un amministratore.');

        $user = User::updateOrCreate(["id" => $id], $request->only((new User())->getFillable()));

        /*         if ( $request->has('meta') ) {
            foreach ($request->input('meta') as $chiave => $valore) {
                $user->meta()->updateOrCreate(["chiave" => $chiave], ["valore" => $valore]);
            }
        }  */
        return response(new UserResource($user));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($user = User::findOrFail($id)) {
            $user->delete();
            return response()->json(['message' => 'Eliminato']);
        }
    }
}
