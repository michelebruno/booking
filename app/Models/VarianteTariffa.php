<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VarianteTariffa extends Model
{
    protected $table = "varianti_tariffa";

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function tariffe()
    {
        return $this->belongsTo('App\Models\Tariffa');
    }

    public function prodotti()
    {
        return $this->hasManyThrough('App\Prodotto', 'App\Models\Tariffa');
    }
}
