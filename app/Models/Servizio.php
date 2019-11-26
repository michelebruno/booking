<?php

namespace App\Models;

use App\Prodotto;
use Illuminate\Database\Eloquent\Builder;

class Servizio extends Prodotto
{
    /*
     * Imposta i valori di default
     * 
     */
    protected $attributes = [
        'tipo' => 'servizio'
    ];

    protected $fillable = [
        'titolo' , 'descrizione', 'codice', 'stato', 'iva', 'wp', 'disponibili'
    ];

    /*
     * Aggiunge al ::toArray()
     */

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

    public function scopeDi($query, $id)
    {
        return $query->where( 'esercente_id' , $id );
    }

}
