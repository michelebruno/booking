<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\OrdineMeta
 *
 * @property int $id
 * @property string $ordine_id
 * @property string $chiave
 * @property string $valore
 * @property-read \App\Ordine $ordine
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdineMeta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdineMeta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdineMeta query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdineMeta whereChiave($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdineMeta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdineMeta whereOrdineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdineMeta whereValore($value)
 * @mixin \Eloquent
 */
class OrdineMeta extends Model
{
    protected $table = 'ordini_meta';

    protected $fillable = [
        'chiave', 'valore'
    ];

    public $timestamps = false;

    public function ordine()
    {
        return $this->belongsTo(\App\Ordine::class, 'ordine_id');
    }
}
