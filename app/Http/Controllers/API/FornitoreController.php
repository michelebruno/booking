<?php

namespace App\Http\Controllers\API;

use App\Fornitore;
use App\Http\Controllers\Controller;
use App\Notifications\Welcome;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class FornitoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Fornitore::class);

        $per_page = $request->query("per_page", 10);

        $query = Fornitore::withTrashed();

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
        $this->authorize('create', Fornitore::class);

        // TODO Validazione della richiesta
        $dati = $request->validate([
            'email' => ['required', 'email', 'unique:users'],
            'indirizzo.cap' => ['sometimes', 'numeric', 'digits:5'],
            'indirizzo.via' => ['sometimes', 'string'],
            'indirizzo.civico' => ['sometimes', 'string'],
            'indirizzo.provincia' => ['sometimes', 'string'],
            'indirizzo.citta' => ['sometimes', 'string'],
            'sede_legale.cap' => ['required', 'numeric', 'digits:5'],
            'sede_legale.via' => ['required', 'string'],
            'sede_legale.civico' => ['required', 'string'],
            'sede_legale.provincia' => ['required', 'string'],
            'sede_legale.citta' => ['required', 'string'],
            'username' => ['required', 'unique:users'],
            'piva' => ['required', 'digits:11', 'unique:users'],
            'cf' => ['required', 'max:16', 'unique:users'], // ? TODO verificare il formato
            'nome' => ['string', 'required'],
            'sdi' => ['sometimes', 'nullable', 'max:7'],
            'pec' => ['sometimes', 'nullable', 'email'],
            'ragione_sociale' => ['required', 'sometimes', 'string']
        ]);

        $user = new Fornitore($dati);

        if (!$user->save()) {
            return abort(500, "Non sono riuscito a salvare il fornitore.");
        }

        $user->markEmailAsVerified();

        $user->notify(new Welcome(true));

        return response($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $fornitore
     * @return \Illuminate\Http\Response
     */
    public function show($fornitore)
    {
        $fornitore = Fornitore::withTrashed()->findOrFail($fornitore);

        $this->authorize('view', $fornitore);

        return response($fornitore->loadMissing('forniture'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Fornitore  $fornitore
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fornitore $fornitore)
    {
        $this->authorize('update', $fornitore);

        $dati = $request->validate([
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($fornitore->email, 'email')],
            'meta.*' => 'nullable',
            'indirizzo.*' => 'nullable',
            'username' => ['required', Rule::unique('users', 'username')->ignore($fornitore->username, 'username')],
            'piva' => ['required', 'digits:11', Rule::unique('users', 'piva')->ignore($fornitore->piva, 'piva')],
            'cf' => ['required', 'max:16', Rule::unique('users', 'cf')->ignore($fornitore->cf, 'cf')],
            'nome' => ['string', 'required'],
            'sdi' => ['sometimes', 'nullable', 'max:7'],
            'pec' => ['sometimes', 'nullable', 'email']
        ]);

        $fornitore->fill($dati); // ? TODO: quanto è sicuro? L'attributo fillable come è impostato?

        $fornitore->nome = $request->input('nome', false);

        $fornitore->sdi = $request->input('sdi', false);

        $fornitore->pec = $request->input('pec', false);

        $fornitore->indirizzo = $request->input('indirizzo', false);

        $fornitore->sede_legale = $request->input('sede_legale', false);

        $fornitore->ragione_sociale = $request->input('ragione_sociale', false);

        $fornitore->save();

        $metas = [];

        return response($fornitore);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Fornitore  $fornitore
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fornitore $fornitore)
    {
        $this->authorize('delete', $fornitore);

        $fornitore->delete();

        return response($fornitore, 200);
    }

    public function restore($fornitore)
    {
        $this->authorize('restore', $fornitore = Fornitore::onlyTrashed()->findOrFail($fornitore));  // ? oppure withTrashed?

        if ($fornitore->restore()) {
            return response($fornitore);
        } else abort(400);
    }
}
