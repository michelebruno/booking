<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\VarianteTariffa
 *
 * @property int $id
 * @property string $slug
 * @property string $nome
 * @property int|null $fallback_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Prodotto[] $prodotti
 * @property-read int|null $prodotti_count
 * @property-read \App\Tariffa $tariffe
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VarianteTariffa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VarianteTariffa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VarianteTariffa query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VarianteTariffa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VarianteTariffa whereFallbackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VarianteTariffa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VarianteTariffa whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VarianteTariffa whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VarianteTariffa whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

    public static function slug($slug)
    {
        return self::where('slug', $slug)->firstOrFail();
    }
}
