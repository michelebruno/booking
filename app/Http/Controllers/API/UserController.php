<?php

namespace App\Http\Controllers\API;

use App\Fornitore;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Notifications\Welcome;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Gestisce le richieste di risorse Utente.
 *
 * @todo dovrebbe esserci un UtentiController più specifico per admin/account_manager/fornitori.
 * @group Users
 */
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     * @todo Impostare i filtri
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
     * Salva un nuovo utente nel database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $dati = $request->validate([
            'email' => ['required', 'email', 'unique:users', 'confirmed'],
            'ruolo' => ['required', Rule::in(\App\User::RUOLI)],
            'nome' => 'nullable',
            'cognome' => 'nullable',
            'username' => ['required', 'unique:users', 'not_regex:/^.+@.+$/i']
        ]);

        if ($request->input('ruolo') == 'admin' && !$request->user()->isSuperAdmin())
            throw new AuthorizationException("Non hai i permessi per creare un amministratore.");

        $user = new User($dati);

        if (!$user->save()) {
            abort(500, "Non è stato possibile salvare l'utente creato.");
        }

        $user->markEmailAsVerified();

        $user->notify(new Welcome(true));

        return response(new UserResource($user));
    }

    /**
     * Mostra un utente specifico.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
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
     * Aggiorna i dati di un utente nel database.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     * @todo Non è il caso di $request->all(). Meglio validare i dati.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        if ($request->input('ruolo') == 'admin' && !$request->user()->isSuperAdmin())
            throw new AuthorizationException("Non hai i permessi per creare un amministratore.");

        $user->update($request->all());
        // $user = User::updateOrCreate(["_id" => $id], $request->only((new User())->getFillable()));

        return response(new UserResource($user));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response("Eliminato", 204);


    }
}
