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
        "_links"
    ];

    public function voci()
    {
        return $this->hasMany(VoceOrdine::class);
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
}
