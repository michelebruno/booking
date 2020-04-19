<?php

namespace App;

use Jenssegers\Mongodb\Collection;
use Jenssegers\Mongodb\Eloquent\Model as MongoModel;
use MongoDB\BSON\ObjectId;
use Arr;

/**
 * App\Importo
 *
 * @property ObjectId $_id
 * @property string slug
 * @property string nome
 * @property float importo
 * @property float imponibile
 * @property int iva
 * @property int tariffa_id
 * @property Tariffa tariffa
 * @property Prodotto parent
 */
class Importo extends MongoModel
{

    protected $appends = [
        'imponibile',
        "iva_ereditata"
    ];

    public $fillable = [
        'tariffa_id',
        'importo',
        'slug',
        'imponibile'
    ];

    protected $hidden = [
        "_id",
        "tariffa_id",
        "tariffa"
    ];

    public $timestamps = false;

    /**
     * @todo Rinominarlo.
     * è usato per ridurre le query.
     */
    public function getTariffaAttribute()
    {
        return app('Tariffe')->firstWhere('id', $this->tariffa_id);
    }

    public function tariffa()
    {
        return $this->belongsTo(Tariffa::class, 'tariffa_id');
    }

    public function getSlugAttribute()
    {
        return $this->tariffa->slug;
    }

    /**
     * @param $slug
     * @return void
     */
    public function setSlugAttribute($slug)
    {
        /** @var Tariffa[]|Collection $varianti */
        $varianti = app('Tariffe');

        if ($variante = $varianti->has('slug', $slug)) {
            $this->tariffa()->associate( $variante[$slug] );
        } else abort(422, 'La variante indicata non esiste.');
    }

    public function getParentAttribute()
    {
        return $this->getParentRelation()->getParent();
    }

    public function getNomeAttribute()
    {
        return $this->tariffa->nome;
    }

    public function getImponibileAttribute()
    {
        return self::escludiIva($this->importo, $this->iva);
    }

    public function setImponibileAttribute(float $value)
    {
        return $this->importo = self::includiIva($value, $this->iva);
    }

    public function getIvaEreditataAttribute()
    {
        return ! Arr::has($this->attributes, "iva");
    }

    public function toArray()
    {
        return array_merge($this->tariffa->makeHidden('id')->toArray(), parent::toArray());
    }


    /**
     * @todo
     */
    public function getIvaAttribute()
    {
        /**
         * Verifica se è stata impostata un'iva per l'importo in questione.
         */
        if (Arr::has( $this->attributes, "iva"))
            return $this->attributes["iva"];

        /**
         * Prende l'iva dal prodotto genitore.
         *
         * @var Prodotto $parent
         */
        return $this->parent->iva;
        /**
         * @todo Potrebbe restituire l'iva di default del sistema.
         */
     }

    /**
     * Dato l'importo totale, restituisce l'imponibile.
     *
     * @param float $importo
     * @param int $iva
     * @return false|float
     */
    public static function includiIva(float $importo, int $iva)
    {
        return round($importo * (1 + $iva / 100), 2);
    }

    public static function escludiIva($imponibile, $iva)
    {
        return round($imponibile / (1 + $iva / 100), 2);
    }
}
