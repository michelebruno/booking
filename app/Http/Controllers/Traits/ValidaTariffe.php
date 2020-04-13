<?php

namespace App\Http\Controllers\Traits;

use App\Prodotto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Trait per i controller che validano le tariffe.
 */
trait ValidaTariffe
{
    public function validaTariffa(Request $request, Prodotto $prodotto)
    {
        return $request->validate([
            'variante' => ['required', 'exists:varianti_tariffa,id', Rule::unique('mongodb.prodotti', 'tariffe.variante_tariffa_id')->where('_id', $prodotto->_id)],
            'importo' => 'numeric|required'
        ]);
    }
}
