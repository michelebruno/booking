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


    protected $fillable = [
        'titolo', 'descrizione', 'codice', 'stato', 'iva', 'wp', 'disponibili'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function (self $model ) {
            $model->tipo = self::TIPO_DEAL;
        });
        static::addGlobalScope('tipo_deal', function (Builder $builder) {
            return $builder->where("tipo", self::TIPO_DEAL);
        });
    }

    /**
     * @todo Da adattare a MongoDB.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|\Jenssegers\Mongodb\Relations\BelongsToMany
     */
    public function forniture()
    {
        return $this->belongsToMany(Fornitura::class, "deal_ids");
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
