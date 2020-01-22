<?php

namespace App;

use App\Models\VoceOrdine;
use Illuminate\Database\Eloquent\Model;

class Ordine extends Model
{
    protected $table = "ordini";

    protected $attributes = [
        'stato' => 'processing'
    ];

    protected $appends = [
        "_links" , "meta"
    ];

    public function voci()
    {
        return $this->hasMany(VoceOrdine::class);
    }

    public function meta()
    {
        return $this->hasMany(\App\Models\OrdineMeta::class );
    }

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente');
    }

    /* ATTRIBUTI */

    public function getLinksAttribute()
    {
        return [
            'self' => "/ordini/" . $this->id
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
}
