<?php

namespace App\Http\Controllers;

use App\Deal;
use App\Ordine;
use App\VoceOrdine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class CartController extends Controller
{
    public function index(Request $request)
    {
        return view('cart');
    }

    public function store(Request $request)
    {
        $dati = $request->validate([
            'tariffa' => 'required|exists:varianti_tariffa,slug',
            'quantita' => 'integer|required|min:1',
            'prodotto' => 'required|exists:prodotti,codice',
        ]);

        $prodotto = Deal::whereCodice( $dati['prodotto'] )->firstOrFail();

        $tariffa = $prodotto->tariffe()->whereVarianteTariffaId( app( 'VariantiTariffe' )->firstWhere('slug', $dati['tariffa'])->id )->firstOrFail();
        
        $voceOrdine = new VoceOrdine();

        $voceOrdine->tariffa()->associate($tariffa);

        $voceOrdine->quantita = $dati['quantita'];

        $request->session()->push('carrello', $voceOrdine );

        return back()->with('status', 'Hai aggiunto al carrello il prodotto ' . $voceOrdine->descrizione );
    }

    public function chiudi(Request $request)
    {
        if ( $voci = session('carrello', false) ) {

            $ordine = new Ordine;

            $ordine->cliente()->associate($request->user() );
    
            $ordine->save();
    
            $ordine->voci()->saveMany( $voci );
    
            $ordine->calcola();
    
            $ordine->saveOrFail();

            return redirect( route('cart.payment', [ 'ordine' => $ordine->id ])  );
    
        } else {
            abort(400, 'Non hai nessun elemento nel carrello.');
        }
    }
    
    public function checkout(Request $request)
    {
        # code...
    }
    
    public function deleteIndex(Request $request, int $index)
    {
        $request->session()->forget( "carrello.$index" );

        return back();
    }
    
    public function destroy(Request $request)
    {
        $request->session()->forget('carrello');

        return back();
    }
}
