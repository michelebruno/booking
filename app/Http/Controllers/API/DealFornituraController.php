<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Deal;
use App\Fornitura;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * @group Deal - Forniture
 */
class DealFornituraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Deal $deal)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Deal $deal)
    {
        $dati = $request->validate([
            'fornitura' => [
                'required',
                Rule::exists('prodotti', 'codice')->where('tipo', \App\Prodotto::TIPO_FORNITURA)
            ]
        ]);

        $this->authorize('create-fornitura', [$deal]);

        $fornitura = Fornitura::whereCodice($dati['fornitura'])->firstOrFail();

        $dati = $request->validate([
            'fornitura' => [
                function ($attribute, $value, $fail) use ($fornitura, $deal) {
                    if ($deal->forniture->find($fornitura->id)) {
                        $fail($attribute . " è già associato al deal.");
                    }
                }
            ]
        ]);

        $deal->forniture()->attach($fornitura->id);

        if ($request->query('from', false) == 'fornitura') return response($fornitura->loadMissing('deals'));

        return response($deal->loadMissing('forniture'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function show(Deal $deal, Fornitura $fornitura)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deal $deal, Fornitura $fornitura)
    {
        $this->authorize('update-fornitura', [$deal, $fornitura]);

        $dati = $request->validate(['forniture' => 'required|array']);

        $deal->forniture()->sync($dati['forniture']);

        if ($request->query('from', false) == 'fornitura') return response($fornitura->loadMissing('deals'));

        return response($deal);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Deal $deal, Fornitura $fornitura)
    {
        $this->authorize('delete-fornitura', [$deal, $fornitura]);

        $deal->forniture()->detach($fornitura);

        if ($request->query('from', false) == 'fornitura') return response($fornitura->loadloadMissing('deals'));

        return response($deal->loadMissing('forniture'));
    }
}
