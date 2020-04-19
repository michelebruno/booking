<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ValidaTariffe;
use App\Deal;
use App\Importo;
use App\Tariffa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


/**
 * @group Deal
 * @authenticated
 */
class DealTariffeController extends Controller
{
    use ValidaTariffe;

    public function __construct()
    {
        $this->middleware("auth:api");
    }

    /**
     * Display a listing of the resource.
     *
     * @param Deal $deal
     * @return \Illuminate\Http\Response
     */
    public function index(Deal $deal)
    {
        return response($deal->tariffe);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Deal $deal
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @bodyParams variante string è lo slug della tariffa che si vuole aggiungere. Example: intero
     * @bodyParams importo float è lo slug della tariffa che si vuole aggiungere. Example: 12.40
     * @todo Authorize.
     */
    public function store(Request $request, Deal $deal)
    {

        $alreadyAssignedTariffe = $deal->tariffe->map(function ($tariffa) {
            return $tariffa->slug;
        });

        $dati = $request->validate([
            'variante' => [
                'required',
                Rule::exists("mysql.varianti_tariffa", 'slug'),
                Rule::notIn($alreadyAssignedTariffe),
            ],
            'importo' => 'numeric|required'
        ]);

        $tariffa = new Importo();

        $tariffa->importo = $dati["importo"];

        $tariffa->tariffa()->associate(app('Tariffe')->firstWhere('slug', $dati['variante']));

        $deal->tariffe()->associate($tariffa);

        $deal->save();

        return response($deal->loadMissing('forniture'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Deal $deal
     * @param Tariffa $variante
     * @return \Illuminate\Http\Response
     */
    public function show(Deal $deal, Tariffa $variante)
    {
        return response($deal->getImportoFromTariffa($variante));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Deal $deal
     * @param Tariffa $tariffa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deal $deal, Tariffa $variante)
    {
        $importo = $deal->getImportoFromTariffa($variante);

        $d = $request->validate([
            'importo' => [
                'required_if:imponibile,null',
                'numeric',
                'gte:0',
            ],
            "imponibile" => [
                'numeric',
                'gte:0',
                'sometimes'
            ]
        ]);

        if ($request->has('imponibile') && $d["imponibile"])
            $importo->imponibile = $d['imponibile'];
        else $importo->importo = $d["importo"];

        $importo->save();

        return response($deal->loadMissing('forniture'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Deal $deal
     * @param Tariffa $variante
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Deal $deal, Tariffa $variante)
    {
        $importo = $deal->getImportoFromTariffa($variante);

        // $this->authorize('delete', [$importo, $deal]);

        $importo->delete();

        return response($deal->loadMissing('forniture'));
    }
}
