<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'chiave', 'valore', 'autoload'
    ];

    protected $primaryKey = "chiave";

    public $incrementing = false;
    
    public function scopeAutoloaded($query)
    {
        return $query->where('autoload', true );
    }
}
