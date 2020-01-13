<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\VoceOrdine;
use App\Ordine;
use Illuminate\Http\Request;

class VoceOrdineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Ordine $ordine)
    {
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VoceOrdine  $voce 
     * @return \Illuminate\Http\Response
     */
    public function show(Ordine $ordine, VoceOrdine $voce )
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VoceOrdine  $voce 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ordine $ordine, VoceOrdine $voce )
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VoceOrdine  $voce 
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ordine $ordine, VoceOrdine $voce )
    {
        //
    }
}
