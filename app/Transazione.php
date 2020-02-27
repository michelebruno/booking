<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Transazione
 *
 * @property int $id
 * @property string $gateway
 * @property string $transazione_id
 * @property string|null $stato
 * @property float $importo
 * @property string $ordine_id
 * @property string|null $verified_by_event_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $verificata
 * @property-read \App\Ordine $ordine
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione transazioneId($id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione whereGateway($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione whereImporto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione whereOrdineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione whereStato($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione whereTransazioneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione whereVerifiedByEventId($value)
 * @mixin \Eloquent
 */
class Transazione extends Model
{
    public $table = "transazioni";

    protected $guarded = [ 'gateway' , 'id' ];

    public function ordine()
    {
        return $this->belongsTo('App\Ordine');
    }

    public function scopeTransazioneId($query, $id)
    {
        return $query->where('transazione_id', $id);
    }

    public function getVerificataAttribute()
    {
        return $this->verified_by_event_id ? true : false;
    }
}
