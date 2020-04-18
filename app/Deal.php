<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

/**
 * App\Deal
 *
 */
class Deal extends Prodotto
{

    const TIPO = self::TIPO_DEAL;

    protected $attributes = [
        'tipo' => self::TIPO_DEAL
    ];

    protected $fillable = [
        'titolo', 'descrizione', 'codice', 'stato', 'iva', 'wp', 'disponibili'
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('tipo_deal', function (Builder $builder) {
            return $builder->where("tipo", self::TIPO_DEAL);
        });
    }

    public function forniture()
    {
        return $this->belongsToMany(Fornitura::class, 'prodotti_pivot', 'padre', 'figlio');
    }

    public function getLinksAttribute()
    {

        $links = [
            'self' => '/deals/' . $this->codice,
            'tariffe' => '/deals/' . $this->codice . '/tariffe',
            'forniture' => '/deals/' . $this->codice . '/forniture',
        ];

        if ($this->trashed()) {
            $links['restore'] = '/deals/' . $this->codice . '/restore';
        }

        return $links;
    }
}
