<?php

namespace App;

use App\Prodotto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

/**
 * App\Fornitura
 *
 * @property int $id
 * @property string $titolo
 * @property string $codice
 * @property string $tipo
 * @property string|null $descrizione
 * @property int|null $fornitore_id
 * @property string $stato
 * @property int|null $disponibili
 * @property int $iva
 * @property int|null $wp
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Deal[] $deals
 * @property-read int|null $deals_count
 * @property-read \App\Fornitore|null $fornitore
 * @property-read mixed $cestinato
 * @property-read mixed $condensato
 * @property-read mixed $links
 * @property-read string $smart
 * @property \Illuminate\Database\Eloquent\Collection|\App\Tariffa[] $tariffe
 * @property-read int|null $tariffe_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto disponibili($more_than = 0)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitura fornitoDa($id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitura newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitura newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitura query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitura whereCodice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitura whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitura whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitura whereDescrizione($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitura whereDisponibili($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitura whereFornitoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitura whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitura whereIva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitura whereStato($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitura whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitura whereTitolo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitura whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitura whereWp($value)
 * @mixin \Eloquent
 */
class Fornitura extends Prodotto
{
    const TIPO = self::TIPO_FORNITURA;

    protected $attributes = [
        'tipo' => self::TIPO_FORNITURA
    ];

    protected $fillable = [
        'titolo', 'descrizione', 'codice', 'stato', 'iva', 'wp', 'disponibili'
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('TIPO_FORNITURA', function (Builder $builder) {
            return $builder->where('tipo', self::TIPO_FORNITURA);
        });
    }

    /**
     * RELATIONSHIP
     */

    public function deals()
    {
        return $this->belongsToMany('App\Deal', 'prodotti_pivot', 'figlio', 'padre');
    }

    public function fornitore()
    {
        return $this->belongsTo('App\Fornitore', 'fornitore_id', 'id')->withTrashed();
    }

    public function getCondensatoAttribute()
    {
        $euro = Arr::exists($this->tariffe, 'intero') ? " | " . " €" . $this->tariffe['intero']->imponibile : '';

        return $this->fornitore->nome . " - " . $this->codice . " - " . $this->titolo . $euro;
    }

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
