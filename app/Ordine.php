<?php

namespace App;

use App\VoceOrdine;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
/**
 * 
 * 
 * 
 * @property string stato
 *      - INIT se è in fase di creazione
 *      - APERTO quando deve essere pagato dal cliente
 *      - ELABORAZIONE se il pagamento è in stato di verifica
 *      - PAGATO se è stato pagato ma non sono stati generati i ticket
 *      - ELABORATO se i tickets stati generati e inviati
 *      - CHIUSO se tutti i tickets sono stati usati
 *      - RIMBORSATO se è stato rimborsato // ? Anche solo parzialmente?
 */
class Ordine extends Model
{

    const INIT = "INIT"; //  se è in fase di creazione
    const APERTO = "APERTO"; //  quando deve essere pagato dal cliente
    const ELABORAZIONE = "ELABORAZIONE"; //  se il pagamento è in stato di verifica
    const PAGATO = "PAGATO"; //  se è stato pagato ma non sono stati generati i ticket
    const ELABORATO = "ELABORATO"; //  se i tickets stati generati e inviati
    const CHIUSO = "CHIUSO"; //  se tutti i tickets sono stati usati
    const RIMBORSATO = "RIMBORSATO"; //  se è stato rimborsato 

    protected $table = "ordini";

    public $incrementing = false;

    public $keyType = "string";

    protected $attributes = [
        'stato' => 'INIT'
    ];

    protected $appends = [
        "_links" , "meta", "links"
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
        $meta = [];

        $array = $this->meta()->get();

        foreach ( $array as $m ) {
            $meta[$m->chiave] = $m->valore;
        }

        return $meta;
    }

    public function completo()
    {
        return $this->load(['cliente' , 'transazioni', 'voci.tickets']);
    }

    public function statoElaborazionePagamento()
    {
        $this->stato = "Pagamento in elaborazione";
    }

    public static function id()
    {

        $y = date('Y');
        
        return $y . "-" . sprintf('%08d', Setting::progressivo( 'ordini' , $y )->valore );
    }

}
