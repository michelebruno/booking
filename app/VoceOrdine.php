<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * 
 * @property  string  $ordine_id 
 * @property  int  $prodotto_id 
 * @property  string  $codice 
 * @property  int  $quantita 
 * @property  float{10,2}  $costo_unitario
 * @property  \App\Prodotto  $prodotto
 * @property  \App\Tariffa  $tariffa
 * @property  \App\Tickets[]  $tickets
 *  
 */
class VoceOrdine extends Model
{
    protected $table = "ordini_voci";

    protected $fillable = [
        'tariffa_id', 'quantita'
    ];

    protected $hidden = [
        'created_at' , 'updated_at'
    ];

    protected $appends = [
        "riscattati"
    ];

    public function riduciDisponibili( int $quantità, bool $salva = true )
    {
        return $this->prodotto->riduciDisponibili( $quantità , $salva );
    }

    public function prodotto()
    {
        return $this->belongsTo('App\Prodotto');
    }

    public function tariffa()
    {
        return $this->belongsTo('App\Tariffa');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, "voce_ordine_id");
    }

    public function getRiscattatiAttribute()
    {
        $cache_key = 'voci_ordini_' . $this->id . "_riscattati";

        return Cache::get( $cache_key , function() use ( $cache_key )
        {
            $r = 0;

            foreach ($this->tickets as $value) {

                if ($value->stato !== "APERTO" ) {
                    $r++;
                }
            }

            Cache::rememberForever($cache_key, function () use ( $r ) {
                return $r;
            });
            return $r;
        });
    }

    public function setTariffaIdAttribute($t)
    {
        $tariffa = Tariffa::findOrFail($t);

        // ? o meglio $this->tariffa()->associate($tariffa); 
        $this->attributes['tariffa_id'] = $tariffa->id;

        $prodotto = $tariffa->prodotto; 
        
        $this->prodotto_id = $prodotto->id;

        $this->codice = $prodotto->codice;
        
        $this->descrizione = $prodotto->titolo . " - " . $tariffa->variante->nome;

        $this->costo_unitario = $tariffa->importo;
        
        $this->iva = $prodotto->iva;
    }

    // TODO dovrebbe essere self::creating() ?

    public function setQuantitaAttribute($quantita)
    {
        $this->attributes['quantita'] = $quantita;

        $this->importo = $this->costo_unitario * $quantita;

        $this->imponibile = round( $this->importo / ( 1 + $this->iva / 100 ) , 2 );

        $this->imposta = round( $this->importo - $this->imponibile , 2 );

    }

    
}
