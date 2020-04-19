<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

/**
 * App\Fornitura
 *
 */
class Fornitura extends Prodotto
{
    const TIPO = self::TIPO_FORNITURA;

    protected $fillable = [
        'titolo', 'descrizione', 'codice', 'stato', 'iva', 'wp', 'disponibili', 'tariffe'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function (self $model) {
            $model->tipo = self::TIPO_FORNITURA;
        });

        static::addGlobalScope('TIPO_FORNITURA', function (Builder $builder) {
            return $builder->where('tipo', self::TIPO_FORNITURA);
        });
    }

    /**
     * RELATIONSHIP
     */

    public function deals()
    {
        return $this->belongsToMany(Deal::class );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|\Jenssegers\Mongodb\Relations\BelongsTo
     */
    public function fornitore()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return $this->belongsTo(Fornitore::class, 'fornitore_id')->withTrashed();
    }

    public function getCondensatoAttribute()
    {
        $euro = Arr::exists($this->tariffe, 'intero') ? " | " . " €" . $this->tariffe['intero']->imponibile : '';

        return $this->fornitore->nome . " - " . $this->codice . " - " . $this->titolo . $euro;
    }

    /**
     * ? Non è un po' inutile dato che si potrebbe usare Fornitore()->foniture ?
     */
    public function scopeFornitoDa($query, $id)
    {
        return $query->where('fornitore_id', $id);
    }

    public function getLinksAttribute()
    {
        $links['self'] = "/fornitori/" . $this->fornitore_id . "/forniture/" . $this->codice;

        $links['tariffe'] = $links["self"] . '/tariffe';

        if ($this->trashed()) $links['restore'] = $links['self'] . "/restore";

        return $links;
    }
}
