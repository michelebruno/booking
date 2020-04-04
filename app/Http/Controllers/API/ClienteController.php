<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $this->authorize('viewAny', Cliente::class);

        $per_page = $request->query("per_page", 10);

        $query = Cliente::with([]);

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
        $dati = $request->validate([
            'email' => 'required|unique:users,email',
            'tipo' => 'required|in:privato,business',
            'cellulare' => 'string',
            'consenso' => 'required'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        // TODO $this->authorize();

        return response($cliente->loadMissing('ordini'));
    }

    /**
     * Update the specified resource in storage.
     *
     * * Non deve essere possibile cambiare il campo ruolo.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        // todo

    }

    public function forceDelete(Cliente $cliente)
    {
        $cliente->forceDelete();
        return response(null, 204);
    }
}
