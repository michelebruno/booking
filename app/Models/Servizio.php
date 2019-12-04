<?php

namespace App\Models;

use App\Prodotto;
use Illuminate\Database\Eloquent\Builder;

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

    public function esercente()
    {
        return $this->belongsTo('App\Models\Esercente');
    }

    public function setEsercente($value)
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

    public function deals()
    {
        return $this->belongsToMany('App\Models\Deal', 'prodotti_pivot', 'figlio', 'padre');
    }

    public function scopeFornitore($query, $id)
    {
        return $query->where( 'esercente_id' , $id );
    }

    public function getLinksAttribute()
    {
        $a = [
            'self' => "/esercenti/" . $this->esercente_id . "/servizi/" . $this->id,
            'tariffe' => "/esercenti/" . $this->esercente_id . "/servizi/" . $this->id . '/tariffe' ,
        ];

        if ( $this->deleted_at ) $a['restore'] = $a['self'] . "/restore";

        return $a;
    }
}
