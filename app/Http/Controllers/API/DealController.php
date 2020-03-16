<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Deal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'per_page' => 'int',
            'order' => 'in:asc,desc',
            'order_by' => 'in:created_at,updated_at,codice,titolo,disponibili',
            'stato' => "in:cestinato,pubblico,privato,bozza|nullable",
        ]);

        $per_page = (int) $request->query('per_page', 10);

        $query =  Deal::query();

        /**
         * Filtro dello stato. 
         * "Cestinato" è uno stato fittizio
         */
        if ($request->query("stato") == "cestinato") {
            $query->onlyTrashed();
        } elseif ($stato = $request->query("stato", null)) {
            $request->wherStato($stato);
        }

        $query->orderBy($request->query('order_by', 'created_at'), $request->query('order', 'desc'));

        if ($s = $request->query('s', false)) {

            $s = urldecode($s);

            $query = $query->where('titolo', 'LIKE', '%' . $s . '%')->orWhere('codice', 'LIKE', '%' . $s . '%');
        }

        if ($notAttachedToForniture = $request->query('notAttachedToForniture', false)) { // Separati con la virgola

            if (!$query) $query = Deal::whereDoesntHave('forniture', function (Builder $query) use ($notAttachedToForniture) {
                return $query->whereIn('figlio', explode(',',  $notAttachedToForniture));
            });

            else $query->whereDoesntHave('forniture', function (Builder $query) use ($notAttachedToForniture) {
                return $query->whereIn('figlio', explode(',',  $notAttachedToForniture));
            });
        }

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
        $this->authorize('create', Deal::class);

        $dati = $request->validate([
            'stato' => ['required', 'string', 'in:pubblico,privato,bozza'],
            'titolo' => ['required', 'string'],
            'descrizione' => ['nullable', 'string'],
            'disponibili' => ['integer'],
            'codice' => ['sometimes', 'nullable', 'unique:prodotti'],
            'iva' => ['integer', 'required', 'gte:0'],
            'importo' => [Rule::requiredIf(is_null($request->input('tariffe.intero.importo'))), 'numeric', 'gte:0', 'nullable'],
            'tariffe' => [Rule::requiredIf(is_null($request->importo)), 'array', 'nullable', 'bail'],
            'tariffe.intero' => ['required_if:importo,null', 'array', 'gte:0', 'nullable'],
            'tariffe.*.importo' => ['required_if:tariffe.*.imponibile,null', 'numeric', 'gte:0'],
            'tariffe.*.imponibile' => ['required_if:tariffe.*.importo,null', 'numeric', 'gte:0'],
        ]);

        $prodotto = new Deal($dati);

        $prodotto->save();

        if (array_key_exists("importo", $dati) && !is_null($dati["importo"])) {
            if (!array_key_exists("tariffe", $dati)) {
                $dati["tariffe"] = [];
            }
            $dati["tariffe"]["intero"]["importo"] = $dati["importo"];
        }

        $prodotto->tariffe = $dati['tariffe'];

        $prodotto->save();

        return response($prodotto->loadMissing('forniture'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function show($deal)
    {
        $deal = Deal::withTrashed()->whereCodice($deal)->firstOrFail();

        $this->authorize('view', $deal);

        return response($deal->loadMissing('forniture'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deal $deal)
    {
        $this->authorize('update', $deal);

        $dati = $request->validate([
            'stato' => 'string|in:pubblico,privato,bozza',
            'titolo' => 'string',
            'descrizione' => 'string',
            'disponibili' => 'integer',
            'codice' => Rule::unique('prodotti', 'codice')->ignore($deal->id),
            'iva' => 'integer'
        ]);

        $deal->fill($dati);

        $deal->save();

        return response($deal->loadMissing('forniture'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deal $deal)
    {
        $this->authorize('delete', $deal);

        if ($deal->delete()) {

            return response($deal);
        }
    }

    public function restore($deal)
    {
        $this->authorize('restore', Deal::class);

        $deal = Deal::onlyTrashed()->whereCodice($deal)->firstOrFail();

        if ($deal->restore()) {
            return response($deal->loadMissing('forniture'));
        } else abort(500);
    }
}
