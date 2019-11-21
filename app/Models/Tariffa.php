<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tariffa extends Model
{
    protected $table = "tariffe";

    protected $appends = [
        'variante'
    ];

    protected $hidden = [
        'variante_tariffa_id'
    ];

    public $timestamps = false;

    public function prodotto()
    {
        return $this->belongsTo('App\Prodotto');
    }

    public function variante()
    {
        return $this->belongsTo('App\Models\VarianteTariffa', 'variante_tariffa_id');
    }

    public function getVarianteAttribute()
    {
        return $this->variante()->first();
    }

}
