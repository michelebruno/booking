<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ValidaTariffe;
use App\Deal;
use App\Tariffa;
use App\VarianteTariffa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * @group Deal
 */
class DealTariffeController extends Controller
{
    use ValidaTariffe;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Deal $deal)
    {
        return response($deal->tariffe);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Deal $deal)
    {
        $this->authorize('create', [Tariffa::class, $deal]);

        $dati = $request->validate([
            'variante' => ['required', 'exists:varianti_tariffa,id', Rule::unique('mongodb.prodotti', 'tariffe.variante_tariffa_id')->where('_id', $deal->_id)],
            'importo' => 'numeric|required'
        ]);

        $deal->tariffe()->create(['variante_tariffa_id' => $dati['variante'], 'importo' => $dati['importo']]);

        return response($deal->loadMissing('forniture'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function show(Deal $deal, VarianteTariffa $variante)
    {
        $tariffa = $deal->tariffe->firstWhere('variante_tariffa_id', $variante->id);

        if (!$tariffa instanceof Tariffa) {
            throw new NotFoundHttpException("La tariffa non è stata trovata.");
        }

        $this->authorize('show', [Tariffa::class, $tariffa, $deal]);

        return response($tariffa);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deal $deal, VarianteTariffa $variante)
    {
        $tariffa = $deal->tariffe->firstWhere('variante_tariffa_id', $variante->id);

        if (!$tariffa instanceof Tariffa) {
            throw new NotFoundHttpException("La tariffa non è stata trovata.");
        }

        $this->authorize('update', [$tariffa, $deal]);


        // TODO può essere veramente nullable?

        $d = $request->validate([
            'importo' => 'numeric|nullable'
        ]);

        $tariffa->importo = $d['importo'];

        $tariffa->save();

        return response($deal->loadMissing('forniture'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deal $deal, VarianteTariffa $variante)
    {
        $tariffa = $deal->tariffe->firstWhere('variante_tariffa_id', $variante->id);

        if (!$tariffa instanceof Tariffa) {
            throw new NotFoundHttpException("La tariffa non è stata trovata.");
        }

        $this->authorize('delete', [$tariffa, $deal]);

        $tariffa->forceDelete();

        return response($deal->loadMissing('forniture'));
    }
}
