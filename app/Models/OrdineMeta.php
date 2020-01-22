<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
