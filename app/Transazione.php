<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Transazione
 *
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
