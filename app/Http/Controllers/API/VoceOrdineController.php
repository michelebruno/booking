<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\VoceOrdine;
use App\Ordine;
use Illuminate\Http\Request;

/**
 * @group Ordini - Voci
 */
class VoceOrdineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Ordine $ordine)
    {
        $this->authorize('viewAny', $ordine);

        return $ordine->voci;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Ordine $ordine)
    {
        $this->authorize('create', $ordine);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VoceOrdine  $voce 
     * @return \Illuminate\Http\Response
     */
    public function show(Ordine $ordine, VoceOrdine $voce)
    {
        $this->authorize('viewAny', $ordine);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VoceOrdine  $voce 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ordine $ordine, VoceOrdine $voce)
    {
        $this->authorize('update', $ordine);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VoceOrdine  $voce 
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ordine $ordine, VoceOrdine $voce)
    {
        $this->authorize('destroy', $ordine);
    }
}
