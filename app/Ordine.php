<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * App\Ordine
 *
 * @property $id
 * @property int|float $importo
 * @property false|float $imponibile
 * @property \Illuminate\Database\Eloquent\Collection|VoceOrdine[] $voci
 * @property false|float imposta
 */
class Ordine extends Model
{
    protected $connection = "mongodb";

    protected $collection = "ordini";

    const INIT = "INIT"; //  se è in fase di creazione
    const APERTO = "APERTO"; //  quando deve essere pagato dal cliente
    const CHIUSO = "CHIUSO"; //  se tutti i tickets sono stati usati
    const ELABORAZIONE = "ELABORAZIONE"; //  se il pagamento è in stato di verifica
    const PAGATO = "PAGATO"; //  se è stato pagato ma non sono stati generati i ticket
    const ELABORATO = "ELABORATO"; //  se i tickets stati generati e inviati
    const RIMBORSATO = "RIMBORSATO"; //  se è stato rimborsato

    public $incrementing = false;

    public $keyType = "string";

    protected $attributes = [
        'stato' => 'INIT'
    ];

    protected $appends = [
        "_links", "links"
    ];

    protected $hidden = [
        "_id"
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('non_INIT', function (Builder $builder) {
            return $builder->where('stato', '<>', self::INIT);
        });
    }

    public function getRouteKeyName()
    {
        return "id";
    }

    public function voci()
    {
        return $this->embedsMany(VoceOrdine::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function transazioni()
    {
        return $this->hasMany(Transazione::class);
    }

    /* ATTRIBUTI */
    public function getLinksAttribute()
    {

        $base = "/ordini/" . $this->id;
        return [
            'self' => $base,
            'transazioni' => $base . "/transazioni"
        ];
    }

    /**
     * @deprecated  Use loadAll instead.
     */
    public function completo()
    {
        return $this->loadMissing(self::getAllRelationshipArray());
    }

    public static function getAllRelationshipArray()
    {
        return [
            'cliente',
            //'transazioni',
        ];
    }

    public function loadAll()
    {
        return $this->loadMissing(self::getAllRelationshipArray());
    }

    public function calcola($setAperto = true)
    {
        $this->importo = $this->voci->sum('importo');

        $this->dovuto = $this->voci->sum('importo');

        $this->imponibile = round($this->voci->sum('imponibile'), 2);

        $this->imposta = round($this->voci->sum('imposta'), 2);

        if ($setAperto) {
            $this->stato = Ordine::APERTO;
        }
    }
}
