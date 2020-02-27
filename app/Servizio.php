<?php

namespace App;

use App\Prodotto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

/**
 * App\Servizio
 *
 * @property int $id
 * @property string $titolo
 * @property string $codice
 * @property string $tipo
 * @property string|null $descrizione
 * @property int|null $esercente_id
 * @property string $stato
 * @property int|null $disponibili
 * @property int $iva
 * @property int|null $wp
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Deal[] $deals
 * @property-read int|null $deals_count
 * @property \App\Esercente|null $esercente
 * @property-read mixed $cestinato
 * @property-read mixed $condensato
 * @property-read mixed $links
 * @property-read string $smart
 * @property mixed $tariffe
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto attivi()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereCodice($codice)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto disponibili($more_than = 0)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio fornitore($id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereCodice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereDescrizione($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereDisponibili($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereEsercenteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereIva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereStato($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereTitolo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereWp($value)
 * @mixin \Eloquent
 * @property-read int|null $tariffe_count
 */
class Servizio extends Prodotto
{
    protected $attributes = [
        'tipo' => self::TIPO_SERVIZIO 
    ];

    protected $fillable = [
        'titolo' , 'descrizione', 'codice', 'stato', 'iva', 'wp', 'disponibili'
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('tipo_servizio', function (Builder $builder)
        {
            return $builder->where('tipo', self::TIPO_SERVIZIO );
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
