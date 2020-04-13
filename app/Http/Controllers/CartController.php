<?php

namespace App\Http\Controllers;

use App\{Deal, Ordine, VoceOrdine};
use Illuminate\Http\Request;
use Illuminate\Validation\{Rule, ValidationException};

class CartController extends Controller
{
    public function index(Request $request)
    {
        return view('cart');
    }

    public function store(Request $request)
    {
        /**
         * Il massimo acquistabile dovrebbe essere dinamico
         */
        $dati = $request->validate([
            'prodotto' => ['required', 'bail', 'exists:prodotti,codice'],
            'tariffa' => ['required', 'exists:varianti_tariffa,slug'],
            'quantita' => ['integer', 'required', 'min:1', 'max:20'],
        ]);

        $prodotto = Deal::whereCodice($dati['prodotto'])->firstOrFail();

        $tariffa = $prodotto->tariffe()->whereVarianteTariffaId(app('VariantiTariffe')->firstWhere('slug', $dati['tariffa'])->id)->firstOrFail();

        $voceOrdine = new VoceOrdine();

        $voceOrdine->tariffa()->associate($tariffa);

        $voceOrdine->quantita = $dati['quantita'];

        $request->session()->push('carrello', $voceOrdine);

        return back()->with('status', 'Hai aggiunto al carrello il prodotto ' . $voceOrdine->descrizione);
    }

    public function chiudi(Request $request)
    {
        if ($voci = session('carrello', [])) {

            $this->validaDisponibili($voci);

            $ordine = new Ordine;

            $ordine->cliente()->associate($request->user());

            $ordine->save();

            $ordine->voci()->saveMany($voci);

            $ordine->calcola();

            if ($ordine->save()) {
                return redirect(route('cart.payment', ['ordine' => $ordine->id]));
            };

        } else {
            /**
             * ? Un'array vuota porta soddisfa la condizione per arrivare qui?
             */
            throw ValidationException::withMessages(["carrello" => "Non hai alcun prodotto nel carrello."]);
        }
    }

    /**
     * Controlla se c'è la disponibilità dei prodotti richiesti.
     *
     * @param  \App\VoceOrdine[]  $voci
     * @return  void
     * 
     * @throws  \Illuminate\Validation\ValidationException
     */
    public function validaDisponibili(array $voci)
    {

        $richiesti = [];

        foreach ($voci as $voce) {
            if (array_key_exists($voce->prodotto->id, $richiesti)) {
                $richiesti[$voce->prodotto->id] += $voce->quantita;
            } else {
                $richiesti[$voce->prodotto->id] = $voce->quantita;
            }
        }

        $prodotti = Deal::findOrFail(array_keys($richiesti));

        $nonDisponibili = [];

        /**
         * Esegui il controllo.
         */
        foreach ($richiesti as $prodottoId => $quantità) {
            $prodotto = $prodotti->find($prodottoId);

            if ($prodotto->disponibili < $quantità) {
                $nonDisponibili[$prodotto->codice] = "Siamo spiacenti, non abbiamo abbastanza prodotti in magazzino.";
            }
        }

        /**
         * Aggiungi l'errore e chiudi il processo
         */

        if (count($nonDisponibili)) {
            throw ValidationException::withMessages($nonDisponibili);
        }
    }

    public function checkout(Request $request)
    {
        # code...
    }

    public function deleteIndex(Request $request, int $index)
    {
        $resetIndex = $request->session()->forget("carrello.$index");

        $resetIndex = array_values($request->session()->get("carrello"));

        $request->session()->put("carrello", $resetIndex);

        return back();
    }

    public function destroy(Request $request)
    {
        $request->session()->forget('carrello');

        return back();
    }
}
