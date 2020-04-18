<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\VarianteTariffa
 *
 */
class VarianteTariffa extends Model
{
    protected $table = "varianti_tariffa";

    protected $connection = 'mysql';

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function getRouteKeyName()
    {
        return "slug";
    }

    public function tariffe()
    {
        return $this->belongsTo('App\Tariffa');
    }

    public function prodotti()
    {
        return $this->hasManyThrough('App\Prodotto', 'App\Tariffa');
    }

    public static function slug($slug)
    {
        return self::where('slug', $slug)->firstOrFail();
    }
}
