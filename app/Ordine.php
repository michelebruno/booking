<?php

namespace App;

use App\VoceOrdine;
use Illuminate\Database\Eloquent\Model;

class Ordine extends Model
{
    protected $table = "ordini";

    public $incrementing = false;

    public $keyType = "string";

    protected $attributes = [
        'stato' => 'processing'
    ];

    protected $appends = [
        "_links" , "meta"
    ];

    protected $year;

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
        return $this->load(['cliente' , 'transazioni', 'voci']);
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
