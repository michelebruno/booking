<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VarianteTariffa extends Model
{
    protected $table = "varianti_tariffa";

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function tariffe()
    {
        return $this->belongsTo('App\Tariffa');
    }

    public function prodotti()
    {
        return $this->hasManyThrough('App\Prodotto', 'App\Tariffa');
    }

    public function scopeSlug($query, $slug)
    {
        return $query->where('slug', $slug)->firstOrFail();
    }
}
