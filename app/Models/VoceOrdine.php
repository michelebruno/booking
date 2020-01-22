<?php

namespace App\Models;

use App\Prodotto;
use Illuminate\Database\Eloquent\Model;

class VoceOrdine extends Model
{
    protected $table = "ordini_voci";

    protected $fillable = [
        'tariffa_id', 'quantita'
    ];

    protected $hidden = [
        'created_at' , 'updated_at'
    ];

    public function prodotto()
    {
        return $this->belongsTo('App\Prodotto');
    }

    public function tariffa()
    {
        return $this->belongsTo('App\Models\Tariffa');
    }

    public function setTariffaIdAttribute($t)
    {
        $tariffa = Tariffa::findOrFail($t);

        $this->attributes['tariffa_id'] = $tariffa->id;

        $prodotto = $tariffa->prodotto; 
        
        $this->prodotto_id = $prodotto->id;

        $this->codice = $prodotto->codice;
        
        $this->descrizione = $prodotto->titolo . " - " . $tariffa->variante->nome;

        $this->costo_unitario = $tariffa->importo;
        
        $this->iva = $prodotto->iva;
    }

    public function setQuantitaAttribute($qta)
    {
        $this->attributes['quantita'] = $qta;

        $this->importo = $this->costo_unitario * $qta;

        $this->imponibile = round( $this->importo / ( 1 + $this->iva / 100 ) , 2 );

        $this->imposta = round( $this->importo - $this->imponibile , 2 );

    }

    
}
