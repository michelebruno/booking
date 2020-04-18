<?php

namespace App\Http\Controllers\API;

use App\Fornitore;
use App\Fornitura;
use App\Tariffa;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ValidaTariffe;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * @group Fornitori - Forniture
 */
class FornitoreFornituraController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api");
    }

    use ValidaTariffe;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $fornitore)
    {
        $fornitore = Fornitore::withTrashed()->findOrFail($fornitore);

        $this->authorize('view', $fornitore);

        $per_page = $request->query('per_page', 10);

        $query = Fornitura::fornitoDa($fornitore->id)->with('deals');

        if ($request->query('only_trashed', false))
            $query->onlyTrashed();
        elseif ($request->query('with_trashed', false))
            $query->withTrashed();

        return response($query->paginate($per_page));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Fornitore $fornitore)
    {
        $this->authorize('create', Fornitura::class);

        $dati = $request->validate([
            'stato' => 'required|string|in:pubblico,privato,bozza',
            'titolo' => 'required|string',
            'descrizione' => 'sometimes|string|nullable',
            'disponibili' => 'integer',
            'codice' => 'sometimes|unique:mongodb.prodotti,codice',
            'iva' => 'integer|required',
            'tariffe' => 'array|bail',
            'tariffe.intero.imponibile' => 'required',
            'tariffe.*.imponibile' => 'required'
        ]);

        $fornitura = new Fornitura($dati);

        $fornitura->fornitore()->associate($fornitore->id);

        $fornitura->save();

        return response($fornitura->loadMissing(['deals', 'fornitore']), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Fornitore  $fornitore
     * @param  int  $fornitura
     * @return \Illuminate\Http\Response
     */
    public function show(Fornitore $fornitore, $fornitura)
    {
        $fornitura = Fornitura::withTrashed()->whereCodice($fornitura)->firstOrFail();

        if ($fornitore->id !== $fornitura->fornitore_id) abort(404, 'Questo fornitura non è associato a questo fornitore.');

        $this->authorize('view', $fornitura);

        return response($fornitura->loadMissing(['deals', 'fornitore']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Fornitore  $fornitore
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fornitore $fornitore, Fornitura $fornitura)
    {
        if ($fornitore->id !== $fornitura->fornitore_id) abort(404, 'Questo fornitura non è associato a questo fornitore.');

        $this->authorize('create', $fornitura);

        if ($request->user()->isFornitore()) {

            $dati = $request->validate([
                'stato' => 'string|in:pubblico,privato,bozza',
                'titolo' => 'string',
                'descrizione' => 'string',
                'disponibili' => 'integer',
                'codice' => Rule::unique('mongodb.prodotti', 'codice')->ignore($fornitura->id),
                'iva' => 'integer|',
                'tariffe' => 'array|bail',
                'tariffe.intero.imponibile' => ''
            ]);
        } else {

            $dati = $request->validate([
                'stato' => 'string|in:pubblico,privato,bozza',
                'titolo' => 'string',
                'descrizione' => 'string',
                'disponibili' => 'integer',
                'codice' => Rule::unique('mongodb.prodotti', 'codice')->ignore($fornitura->id),
                'iva' => 'integer|',
                'tariffe' => 'array|bail',
                'tariffe.intero.imponibile' => 'numeric'
            ]);
        }

        $fornitura->fill($dati);

        $fornitura->save();

        $fornitura->tariffe = $request->input('tariffe', null);

        return response($fornitura->loadMissing('deals'));
    }

    public function aggiungiTariffa(Request $request, Fornitore $fornitore, Fornitura $fornitura)
    {
        if ($fornitore->id !== $fornitura->fornitore_id) abort(404, 'Questo fornitura non è associato a questo fornitore.');

        $this->authorize('update', $fornitura);

        $dati = $request->validate([
            'variante' => ['required', 'exists:varianti_tariffa,id', Rule::unique('tariffe', 'variante_tariffa_id')->where('prodotto_id', $fornitura->id)],
            'imponibile' => 'required|numeric'
        ]);

        $fornitura->tariffe()->create(['variante_tariffa_id' => $dati['variante'], 'importo' => Tariffa::includiIva($dati['imponibile'], $fornitura->iva)]);

        return response($fornitura->loadMissing('deals'), 201);
    }

    public function editTariffa(Request $request, Fornitore $fornitore, Fornitura $fornitura, Tariffa $tariffa)
    {
        if ($fornitore->id !== $fornitura->fornitore_id) abort(404, 'Questo fornitura non è associato a questo fornitore.');

        $this->authorize('update', $fornitura);

        $d = $request->validate([
            'imponibile' => 'required|numeric'
        ]);

        if ($tariffa->prodotto_id !== $fornitura->id) return abort(404, "L'id del prodotto non è associato a questa tariffa tariffa.");

        $tariffa->importo = Tariffa::includiIva($d['imponibile'], $fornitura->iva);
        $tariffa->save();

        return response($fornitura->loadMissing('deals'));
    }

    public function deleteTariffa(Request $request, Fornitore $fornitore, Fornitura $fornitura, Tariffa $tariffa)
    {
        if ($fornitore->id !== $fornitura->fornitore_id) abort(404, 'Questo fornitura non è associato a questo fornitore.');
        if ($tariffa->prodotto_id !== $fornitura->id) return abort(404, "L'id del prodotto non è associato a questa tariffa tariffa.");

        $this->authorize('delete', [$tariffa, $fornitura, $fornitore]);

        $tariffa->delete();

        return response($fornitura->loadMissing('deals'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Fornitore  $fornitore
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fornitore $fornitore, Fornitura $fornitura)
    {
        $this->authorize('delete', $fornitura);

        if ($fornitore->id !== $fornitura->fornitore_id) abort(404);

        $fornitura->delete();

        return response(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Fornitore  $fornitore
     * @return \Illuminate\Http\Response
     */
    public function restore(Fornitore $fornitore, $fornitura)
    {
        $this->authorize('restore', $fornitura);

        $fornitura = Fornitura::fornitoDa($fornitore->id)->onlyTrashed()->findOrFail($fornitura);

        $fornitura->restore();

        return response($fornitura->loadMissing('deals'));
    }
}
