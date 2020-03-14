<?php

namespace App;

use App\Prodotto;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Deal
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Fornitura[] $forniture
 * @property-read int|null $forniture_count
 * @property-read mixed $cestinato
 * @property-read mixed $condensato
 * @property-read mixed $links
 * @property-read string $smart
 * @property \Illuminate\Database\Eloquent\Collection|\App\Tariffa[] $tariffe
 * @property-read int|null $tariffe_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto disponibili($more_than = 0)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereCodice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereDescrizione($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereDisponibili($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereFornitoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereIva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereStato($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereTitolo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereWp($value)
 * @mixin \Eloquent
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
            return $builder->whereTipo(self::TIPO_DEAL);
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
