<?php

namespace App;

use App\Traits\HaAttributiMeta;
use App\VoceOrdine;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
/**
 * App\Ordine
 *
 * @property string  $stato
 *      - INIT se è in fase di creazione
 *      - APERTO quando deve essere pagato dal cliente
 *      - ELABORAZIONE se il pagamento è in stato di verifica
 *      - PAGATO se è stato pagato ma non sono stati generati i ticket
 *      - ELABORATO se i tickets stati generati e inviati
 *      - CHIUSO se tutti i tickets sono stati usati
 *      - RIMBORSATO se è stato rimborsato // ? Anche solo parzialmente?
 * @property string $id
 * @property float|null $imponibile
 * @property float|null $imposta
 * @property float|null $importo
 * @property float|null $dovuto
 * @property int $cliente_id
 * @property string|null $data
 * @property string|null $paypal_order_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Cliente $cliente
 * @property-read mixed $links
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OrdineMeta[] $meta
 * @property-read int|null $meta_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Transazione[] $transazioni
 * @property-read int|null $transazioni_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\VoceOrdine[] $voci
 * @property-read int|null $voci_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine whereDovuto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine whereImponibile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine whereImporto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine whereImposta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine wherePaypalOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine whereStato($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine withAll()
 * @mixin \Eloquent
 */
class Ordine extends Model
{

    use HaAttributiMeta;
    
    const INIT = "INIT"; //  se è in fase di creazione
    const APERTO = "APERTO"; //  quando deve essere pagato dal cliente
    const CHIUSO = "CHIUSO"; //  se tutti i tickets sono stati usati
    const ELABORAZIONE = "ELABORAZIONE"; //  se il pagamento è in stato di verifica
    const PAGATO = "PAGATO"; //  se è stato pagato ma non sono stati generati i ticket
    const ELABORATO = "ELABORATO"; //  se i tickets stati generati e inviati
    const RIMBORSATO = "RIMBORSATO"; //  se è stato rimborsato 

    protected $table = "ordini";

    public $incrementing = false;

    public $keyType = "string";

    protected $attributes = [
        'stato' => 'INIT'
    ];

    protected $appends = [
        "_links" , "links"
    ];

    protected $year;

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('non_INIT', function (Builder $builder)
        {
            return $builder->where('stato', '<>', self::INIT);
        });
    }

    public function voci()
    {
        return $this->hasMany(VoceOrdine::class);
    }

    public function meta()
    {
        return $this->hasMany(\App\OrdineMeta::class );
    }

    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }

    public function transazioni()
    {
        return $this->hasMany( \App\Transazione::class );
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

    public function getMetaAttribute()
    {
        $meta = $this->getRelationValue('meta');
        
        return $meta->mapWithKeys(function ($item) {
            return [ $item['chiave'] => $item["valore"] ];
        });
    }

    /**
     * @deprecated  Use loadAll instead.
     */
    public function completo()
    {
        $this->loadMissing(['cliente' , 'transazioni', 'voci.tickets']);

        $this->append('meta');

        return $this;

    }

    public function loadAll()
    {        
        return $this->loadMissing(['cliente' , 'transazioni', 'voci.tickets']);
    }

    public static function withAll( $query )
    {        
        return $query->with(['cliente' , 'transazioni', 'voci.tickets']);

    }

}
