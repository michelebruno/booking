<?php

namespace App;

use Illuminate\Support\Facades\Cache;
use Jenssegers\Mongodb\Eloquent\Model as EloquentModel;

/**
 * App\VoceOrdine
 *
 * @property int|null $prodotto_id
 * @property string $codice
 * @property string|null $descrizione
 * @property float $costo_unitario
 * @property int|null $tariffa_id
 * @property int $quantita
 * @property int $iva
 * @property float $imponibile
 * @property float $imposta
 * @property float $importo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $riscattati
 * @property-read Deal|null $prodotto
 * @property-read VarianteTariffa|null $tariffa
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Ticket[] $tickets
 * @property-read int|null $tickets_count
 */
class VoceOrdine extends EloquentModel
{
    /**
     *
     * @var array
     * @todo è adeguato che siano fillable?
     */
    protected $fillable = [
        'prodotto_id',
        'quantita',
        'tariffa'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $appends = [
        // "riscattati"
    ];

    /**
     * @param int $quantita
     * @param bool $salva Set to true if you want to save immediatly the product.
     * @return mixed
     */
    public function riduciDisponibili(int $quantita, bool $salva = true)
    {
        return $this->prodotto->riduciDisponibili($quantita, $salva);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tariffa()
    {
        return $this->belongsTo(VarianteTariffa::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, "voce_ordine_id");
    }

    /**
     * @return int
     */
    public function getRiscattatiAttribute()
    {
        $cache_key = 'voci_ordini_' . $this->id . "_riscattati";

        return Cache::get($cache_key, function () use ($cache_key) {
            $r = 0;

            foreach ($this->tickets as $value) {

                if ($value->stato !== "APERTO") {
                    $r++;
                }
            }

            Cache::rememberForever($cache_key, function () use ($r) {
                return $r;
            });
            return $r;
        });
    }

    /**
     * @param $t
     * @throws \Exception
     */
    public function setTariffaIdAttribute($t)
    {
        $tag = VarianteTariffa::findOrFail($t);

        $prezzo = $this->prodotto->tariffe->where("variante_tariffa_id", $tag->getKey())->first();

        if (!$prezzo) {
            throw new \Exception("Il prodotto non ha questa tariffa impostata.");
        }

        $this->codice = $this->prodotto->codice;

        $this->descrizione = $this->prodotto->titolo . " - " . $tag->nome;

        $this->costo_unitario = $prezzo->importo;

        $this->iva = $this->prodotto->iva;
    }

    // TODO dovrebbe essere self::creating() ?

    public function setQuantitaAttribute($quantita)
    {
        $this->attributes['quantita'] = $quantita;

        $this->importo = $this->costo_unitario * $quantita;

        $this->imponibile = round($this->importo / (1 + $this->iva / 100), 2);

        $this->imposta = round($this->importo - $this->imponibile, 2);

    }


}
