<?php

namespace App;

use App\Prodotto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class Servizio extends Prodotto
{
    protected $attributes = [
        'tipo' => 'servizio'
    ];

    protected $fillable = [
        'titolo' , 'descrizione', 'codice', 'stato', 'iva', 'wp', 'disponibili'
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('tipo_servizio', function (Builder $builder)
        {
            return $builder->where('tipo', 'servizio');
        });
    }

    /**
     * RELATIONSHIP
     */

    public function deals()
    {
        return $this->belongsToMany('App\Deal', 'prodotti_pivot', 'figlio', 'padre');
    }

    public function esercente()
    {
        return $this->belongsTo('App\Esercente', 'esercente_id')->withTrashed();
    }

    public function setEsercenteAttribute($value)
    {
        if ( $value instanceof Esercente ) {
            return $this->attributes['esercente_id'] = $value->id;
        } elseif ( is_array($value) ) {
            if ( array_key_exists('id', $value ) ) {
                return $this->attributes['esercente_id'] = $value['id'];
            } else abort(400);
        } else {
            return $this->attributes['esercente_id'] = $value;
        }
    }

    public function getCondensatoAttribute()
    {
        $euro = Arr::exists( $this->tariffe, 'intero' ) ? " | " . " €" . $this->tariffe['intero']->imponibile : '' ;

        return $this->esercente->nome . " - " . $this->codice . " - " . $this->titolo . $euro ;
    }

    public function scopeFornitore($query, $id)
    {
        return $query->where( 'esercente_id' , $id );
    }

    public function getLinksAttribute()
    {
        $a = [
            'self' => "/esercenti/" . $this->esercente_id . "/servizi/" . $this->codice,
            'tariffe' => "/esercenti/" . $this->esercente_id . "/servizi/" . $this->codice . '/tariffe' ,
        ];

        if ( $this->trashed() ) $a['restore'] = $a['self'] . "/restore";

        return $a;
    }
}
