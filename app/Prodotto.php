<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Jenssegers\Mongodb\Eloquent\Model as MongoModel;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Arr;
use App;
use MongoDB\BSON\ObjectId;

/**
 * App\Prodotto
 *
 * @property ObjectId $_id
 * @property $titolo
 * @property $descrizione
 * @property $codice
 * @property $stato
 * @property $iva
 * @property int $wp
 * @property $disponibili
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read string $condensato
 * @property-read bool $cestinato
 * @property-read array $links
 * @property Collection|Importo[] tariffe
 * @method static whereCodice($prodotto)
 * @method static whereTipo($prodotto)
 * @method static whereRuolo($prodotto)
 * @method static whereWp($prodotto)
 * @method static where($prodotto)
 * @method static whereTitolo($prodotto)
 * @method static whereStato($prodotto)
 * @method static whereDisponibili($prodotto)
 */
class Prodotto extends MongoModel
{
    use SoftDeletes;

    const TIPO_DEAL = "deal";

    const TIPO_FORNITURA = "fornitura";

    const TIPI = [
        self::TIPO_DEAL,
        self::TIPO_FORNITURA,
    ];

    protected $connection = 'mongodb';

    protected $collection = "prodotti";

    protected $hidden = [
        'fornitore_id', 'deleted_at', 'created_at', 'updated_at'
    ];

    protected $appends = [
        'condensato', 'cestinato', '_links'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($prodotto) {
            if (!$prodotto->codice) {
                $prodotto->codice = $prodotto::progressivo();
            }
        });

        self::created(function () {
            App::forgetInstance('Prodotti');
        });

        self::updated(function () {
            App::forgetInstance('Prodotti');
        });

        self::deleted(function () {
            App::forgetInstance('Prodotti');
        });
    }

    public function getRouteKeyName()
    {
        return 'codice';
    }

    /**
     * @param int $quantita La quantità di cui ridurre la disponibiltà del prodotto.
     * @param bool $salva Decide se aggiornare subito il database.
     * @return bool|int|void
     */
    public function riduciDisponibili(int $quantita, bool $salva = true)
    {
        if ($this->disponibili >= $quantita) {
            $this->disponibili -= $quantita;
            return $salva ? $this->save() : $quantita;
        }
        return abort(400, "Attenzione, non ci sono abbastanza prodotti per quest'ordine.
        Tuttavia dovrebbe esserci già stato un check da parte del controller.");
    }

    /**
     * @return \Jenssegers\Mongodb\Relations\EmbedsMany
     */
    public function tariffe()
    {
        return $this->embedsMany(Importo::class);
    }

    /* MUTATORS *

   /**
     *
     * @abstract
     * @return array
     */
    public function getLinksAttribute()
    {
        return [];
    }

    /**
     * @param Tariffa $tariffa
     * @return mixed
     */
    public function getImportoFromTariffa(Tariffa $tariffa)
    {

        $t = $this->tariffe->firstWhere("tariffa_id", $tariffa->id);

        if (!$t)
            throw new ModelNotFoundException("La tariffa non è associata a questo prodotto.");

        return $t;
    }

    public function setCodiceAttribute($value)
    {
        $value = preg_replace('/\s+/', '-', $value);
        return $this->attributes['codice'] = strtoupper($value);
    }

    /**
     * @param array $tariffe
     * @return void
     * @deprecated
     */
    public function _setTariffeAttribute(array $tariffe)
    {
        foreach ($tariffe as $key => $value) {

            if (!app('VariantiTariffe')->has($key)) {
                return abort(400, "Il tipo di tariffa selezionato non esiste.");
            }

            $etichetta = app('VariantiTariffe')[$key];

            Arr::forget($value, ['variante_tariffa_id', '_id']);

            if (($tariffa = $this->tariffe->firstWhere("variante_tariffa_id", $etichetta->getKey()))) {
                // La tariffa esiste già. Occorre solo aggiornarla
                return $tariffa->update($value);
            }

            // Creamo una nuova tariffa.
            $this->tariffe()->create(array_merge($value, ['variante_tariffa_id' => $etichetta->id]));
        }
    }

    public function getCondensatoAttribute()
    {
        if (!$this->tariffe instanceof Collection && !$this->tariffe->count())
            return $this->codice . " - " . $this->titolo;

        $intero = $this->tariffe->firstWhere('slug', 'intero');

        $euro = $intero ? " | " . " €" . $intero->imponibile : '';

        return $this->codice . " - " . $this->titolo . $euro;
    }

    public function getCestinatoAttribute()
    {
        return $this->trashed();
    }

    /*
     *
     *
     *
     */
    /**
     *
     * @param Builder $query
     * @param int $more_than
     * @return mixed
     * @todo Probabilmente si dovrebbe rifattorizzare il conteggio dei disponibili in base alla disponibilità delle forniture e un overbooking.
     */
    public function scopeDisponibili($query, $more_than = 0)
    {
        return $query->where('disponibili', '>', $more_than);
    }

    public function toArray()
    {
        $array = parent::toArray();

        $tariffe = $this->tariffe->keyBy('slug');

        /**
         * Se non dovesse avere tariffe, deve comunque restituire un oggetto quando trasformato in json
         * @todo forse basta un array vuota?
         */
        $array["tariffe"] = count($tariffe) ? $tariffe : new \stdClass;

        return $array;
    }

    public static function progressivo(...$args)
    {
        $childClass = get_called_class();

        return Setting::progressivoSicuro($childClass::TIPO, [Prodotto::class, 'whereCodice'], strtoupper($childClass::TIPO[0]), ...$args);
    }
}
