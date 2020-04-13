<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrdineRequest;
use App\Http\Resources\OrdineCollection;
use App\Http\Resources\OrdineResource;
use App\Cliente;
use App\Deal;
use App\Tariffa;
use App\VoceOrdine;
use App\Ordine;
use App\User;
use App\VarianteTariffa;
use Illuminate\Http\Request;

/**
 * @group Ordini
 */
class OrdineController extends Controller
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
            'order_by' => 'in:created_at,updated_at,id,importo,imponibile,email'
        ]);

        $per_page = $request->query('per_page', 10);

        $query = Ordine::query();

        $query->with(Ordine::getAllRelationshipArray());

        $query->orderBy($request->input('order_by', 'created_at'), $request->input('order', 'desc'));

        switch (strtoupper($request->query('stato', false))) {
            case 'PAGATI':
                $query->whereIn("stato", [Ordine::ELABORAZIONE, Ordine::PAGATO, Ordine::ELABORATO]);
                break;

            case 'APERTI':
                $query->where("stato", Ordine::APERTO);
                break;
            default:
                break;
        }

        if (!$per_page) {
            return response($query->get());
        }

        return response($query->paginate($per_page));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\StoreOrdineRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrdineRequest $request)
    {
        /**
         * TODO authorize
         * Probabilmente basterrebbe che sia autenticato.
         */
        $request->authorize();

        $dati = $request->validated();

        if (in_array($request->user()->ruolo, [User::RUOLO_ACCOUNT, User::RUOLO_ADMIN])) {

            try {
                $cliente = Cliente::whereEmail($dati["cliente"]["email"])->firstOrFail();
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) {
                $cliente = new Cliente($dati['cliente']);
                $cliente->save();
            }
            
        } else $cliente = $request->user();

        $ordine = new Ordine();

        $ordine->cliente()->associate($cliente);

        foreach ($dati['voci'] as $voce) {

            $prodotto = Deal::whereCodice($voce["prodotto"])->firstOrFail();

            $tag = VarianteTariffa::whereSlug($voce["tariffa"])->firstOrFail();

            $tariffa = $prodotto->tariffe->firstWhere("variante_tariffa_id" , $tag->id);

            if ($prodotto->disponibili < $voce["qta"]) {
                /**
                 *
                 * ? Forse è meglio una ValidationException con un messaggio. 
                 */
                throw new \Exception("Il prodotto non è più disponibile."); 
            }

            $v = new VoceOrdine();

            $v->prodotto()->associate($prodotto);

            $v->tariffa()->associate($tag);

            $v->quantita = $voce["qta"];

            $ordine->voci()->associate($v);
        }

        $ordine->calcola();

        return $ordine->save() 
            ? response($ordine->completo(), 201) 
            : abort(500, "Non è stato possibile salvare l'ordine.");
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ordine  $ordine
     * @return \Illuminate\Http\Response
     */
    public function show(Ordine $ordine)
    {
        $this->authorize('view', $ordine);

        return response($ordine->completo());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ordine  $ordine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ordine $ordine)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ordine  $ordine
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ordine $ordine)
    {
        //
    }
}
