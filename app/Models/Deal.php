<?php

namespace App\Models;

use App\Prodotto;
use Illuminate\Database\Eloquent\Builder;

class Deal extends Prodotto
{
    protected $attributes = [
        'tipo' => 'deal'
    ];

    protected $fillable = [
        'titolo' , 'descrizione', 'codice', 'stato', 'iva', 'wp', 'disponibili'
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('tipo_deal', function (Builder $builder)
        {
            return $builder->where('tipo', 'deal');
        });
    }

    public function servizi()
    {
        return $this->belongsToMany('App\Models\Servizio', 'prodotti_pivot', 'padre', 'figlio');
    }

    public function getLinksAttribute()
    {
        return [
            'self' => '/deals/' . $this->codice,
            'tariffe' => '/deals/' . $this->codice . '/tariffe',
            'servizi' => '/deals/' . $this->codice . '/servizi',
        ];
    }
}
